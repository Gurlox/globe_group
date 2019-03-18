<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Rate;

class RateManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function rateMovie(int $movieId, ?int $rate)
    {
        $movie = $this->em->getRepository(Movie::class)->find($movieId);

        if ($movie) {
            try {
                if (is_null($rate)) {
                    throw new \Exception('Bad value');
                }
                $rate = (new Rate())->setPoints($rate)->setMovie($movie);
                $this->em->persist($rate);
                $this->em->flush();

                return ['status' => Response::HTTP_OK];
            } catch (\Exception $e) {
                return [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $e->getMessage()
                ];
            }
        }

        return [
            'status' => Response::HTTP_NOT_FOUND,
            'message' => "This movie doesn't exist"
        ];
    }
}
