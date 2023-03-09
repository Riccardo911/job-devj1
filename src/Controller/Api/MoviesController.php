<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class MoviesController extends AbstractController
{
    #[Route('/api/movies')]
    public function list(Connection $db): Response
    {
        $rows = $db->createQueryBuilder()
            ->select("m.*")
            ->from("movies", "m")
            ->orderBy("m.release_date", "DESC")
            ->setMaxResults(50)
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->json([
            "movies" => $rows
        ]);
    }

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

    #[Route('/api/movies/genres/{id}')]
    public function genresById(Connection $db, $id): Response
    {
        $rows = $db->createQueryBuilder()
                ->select("m.*")
                ->from("movies", "m")
                ->innerJoin("m", "movies_genres", "mg", "mg.movie_id = m.id")
                ->innerJoin("mg", "genres", "g", "g.id = mg.genre_id")
                ->where("g.id = :genreId")
                ->orderBy("m.title")
                ->setParameter("genreId", $id)
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->json([
            "movies" => $rows
        ]);
    }

    #[Route('/api/movies/orderBy/mostRecents')]
    public function mostRecents(Connection $db): Response
    {
        $rows = $db->createQueryBuilder()
            ->select("m.*")
            ->from("movies", "m")
            ->orderBy("m.release_date", "DESC")
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->json([
            "movies" => $rows
        ]);
    }

    #[Route('/api/movies/orderBy/rating')]
    public function rating(Connection $db): Response
    {
        $rows = $db->createQueryBuilder()
            ->select("m.*")
            ->from("movies", "m")
            ->orderBy("m.rating", "DESC")
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->json([
            "movies" => $rows
        ]);
    }
}
