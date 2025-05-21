<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\{Movie, Serie, Genre};

class ImportTMDBData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import movies, series and genres from TMDB';

    protected $apiKey;
    protected $baseUrl;
    protected $languages = ['en', 'pl', 'de'];

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('services.tmdb.key');
        $this->baseUrl = 'https://api.themoviedb.org/3';
    }

    public function handle()
    {
        try {
            $this->importGenres();
            $this->importMovies(50);
            $this->importSeries(10);
        } catch (Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }
    }

    protected function fetchFromApi(string $endpoint, array $params = []){
        $response = Http::get("{$this->baseUrl}/{$endpoint}", array_merge([
            'api_key' => $this->apiKey
        ], $params));

        if(!$response->successful()){
            throw new Exception("TMDB API error: " . $response->body());
        }

        return $response->json();
    }

    protected function createProgressBar($steps, $message){
        $bar = $this->output->createProgressBar($steps);
        $bar->setFormat("$message: [%bar%] %percent:3s%% %current%/%max%");
        return $bar;
    }

    protected function importGenres(){
        $this->info('Importing genres...');
        
        // Fetch genres in different languages
        $genresByLang = [];
        $languageBar = $this->createProgressBar(count($this->languages), 'Fetching genres for languages');
        
        foreach($this->languages as $lang){
            $response = $this->fetchFromApi('genre/movie/list', [
                'language' => $lang,
            ]);
            $genresByLang[$lang] = $response['genres'];
            $languageBar->advance();
        }
        $languageBar->finish();
        $this->newLine();

        // Process genres
        $tmdbIds = array_column($genresByLang['en'], 'id');
        $genreBar = $this->createProgressBar(count($tmdbIds), 'Processing genres');

        foreach($tmdbIds as $index => $tmdbId){
            $genreEn = $genresByLang['en'][$index];
            $genrePl = $genresByLang['pl'][$index] ?? [];
            $genreDe = $genresByLang['de'][$index] ?? [];

            $genre = Genre::firstOrCreate(['tmdb_id' => $tmdbId]);

            $genre->setTranslations('name', [
                'en' => $genreEn['name'] ?? '',
                'pl' => $genrePl['name'] ?? '',
                'de' => $genreDe['name'] ?? '',
            ]);

            $genre->save();
            $genreBar->advance();
        }
        
        $genreBar->finish();
        $this->newLine();
        $this->info('Genres imported successfully.');
    }

    protected function importMovies($limit){
        $this->info('Importing movies...');
        
        // Fetch movies in different languages
        $moviesByLang = [];
        $languageBar = $this->createProgressBar(count($this->languages), 'Fetching movies for languages');
        
        foreach($this->languages as $lang){
            $response = $this->fetchFromApi('movie/popular', [
                'language' => $lang,
                'page' => 1
            ]);
            $moviesByLang[$lang] = array_slice($response['results'], 0, $limit);
            $languageBar->advance();
        }
        $languageBar->finish();
        $this->newLine();

        // Process movies
        $tmdbIds = array_column($moviesByLang['en'], 'id');
        $movieBar = $this->createProgressBar(count($tmdbIds), 'Processing movies');

        foreach($tmdbIds as $index => $tmdbId){
            $movieDataEn = $moviesByLang['en'][$index];
            $movieDataPl = $moviesByLang['pl'][$index] ?? [];
            $movieDataDe = $moviesByLang['de'][$index] ?? [];

            $movie = Movie::firstOrCreate(['tmdb_id' => $tmdbId]);

            $movie->setTranslations('title', [
                'en' => $movieDataEn['title'] ?? '',
                'pl' => $movieDataPl['title'] ?? '',
                'de' => $movieDataDe['title'] ?? '',
            ]);

            $movie->setTranslations('overview', [
                'en' => $movieDataEn['overview'] ?? '',
                'pl' => $movieDataPl['overview'] ?? '',
                'de' => $movieDataDe['overview'] ?? '',
            ]);

            $movie->save();
            $movieBar->advance();

            // Join genres
            $genreIds = $movieDataEn['genre_ids'] ?? [];
            if($genreIds){
                $genres = Genre::whereIn('tmdb_id', $genreIds)->pluck('id');
                $movie->genres()->sync($genres);
            }
        }
        
        $movieBar->finish();
        $this->newLine();
        $this->info("Movies imported successfully.");
    }

    protected function importSeries($limit){
        $this->info('Importing series...');
        
        // Fetch series in different languages
        $seriesByLang = [];
        $languageBar = $this->createProgressBar(count($this->languages), 'Fetching series for languages');
        
        foreach($this->languages as $lang){
            $response = $this->fetchFromApi('tv/popular', [
                'language' => $lang,
                'page' => 1
            ]);
            $seriesByLang[$lang] = array_slice($response['results'], 0, $limit);
            $languageBar->advance();
        }
        $languageBar->finish();
        $this->newLine();

        // Process series
        $tmdbIds = array_column($seriesByLang['en'], 'id');
        $seriesBar = $this->createProgressBar(count($tmdbIds), 'Processing series');

        foreach($tmdbIds as $index => $tmdbId){
            $serieDataEn = $seriesByLang['en'][$index];
            $serieDataPl = $seriesByLang['pl'][$index] ?? [];
            $serieDataDe = $seriesByLang['de'][$index] ?? [];

            $serie = Serie::firstOrCreate(['tmdb_id' => $tmdbId]);

            $serie->setTranslations('name', [
                'en' => $serieDataEn['name'] ?? '',
                'pl' => $serieDataPl['name'] ?? '',
                'de' => $serieDataDe['name'] ?? '',
            ]);

            $serie->setTranslations('overview', [
                'en' => $serieDataEn['overview'] ?? '',
                'pl' => $serieDataPl['overview'] ?? '',
                'de' => $serieDataDe['overview'] ?? '',
            ]);

            $serie->save();
            $seriesBar->advance();

            // Join genres
            $genreIds = $serieDataEn['genre_ids'] ?? [];
            if ($genreIds) {
                $genres = Genre::whereIn('tmdb_id', $genreIds)->pluck('id');
                $serie->genres()->sync($genres);
            }
        }
        
        $seriesBar->finish();
        $this->newLine();
        $this->info("Series imported successfully.");
    }
}
