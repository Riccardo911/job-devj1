<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class MoviesController extends AbstractController
{

    #[Route('/api/movies/genres')]
    public function genres(Connection $db): Response
    {
        $rows = $db->createQueryBuilder()
            ->select("g.*")
            ->from("genres", "g")
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->json([
            "genres" => $rows
        ]);
    }

    #[Route('/api/movies/{genres}/{sortingCriteria}', defaults: ['genres' => '', 'sortingCriteria' => ''])]
    public function getMovies(Connection $db, $genres, $sortingCriteria): Response
    {
        $qb = $db->createQueryBuilder()
            ->select("m.*")
            ->from("movies", "m");

        if (!empty($genres)) {
            $qb->innerJoin("m", "movies_genres", "mg", "mg.movie_id = m.id")
                ->innerJoin("mg", "genres", "g", "g.id = mg.genre_id")
                ->where("g.id = :genreId")
                ->setParameter("genreId", $genres);
        }

        switch ($sortingCriteria) {
            case 'mostRecents':
                $qb->orderBy("m.release_date", "DESC");
                break;
            case 'rating':
                $qb->orderBy("m.rating", "DESC");
                break;
            default:
                $qb->orderBy("m.title");
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();

        return $this->json([
            "movies" => $rows
        ]);
    }
}
