<?php

namespace JC\BlogBundle\Repository;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogRepository extends \Doctrine\ORM\EntityRepository
{

    public function getLatestBlogs($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.comments', 'c')
            ->addOrderBy('b.created', 'DESC');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb  ->getQuery()
                    ->getResult();
    }

    /**
     * Prend les tag dans la db dans le format CSV (comma separated values, c’est à dire que chaque valeur est séparée
     * de la précédente par une virgule) séparer et de renvoyer le résultat sous la forme d’un tableau
     *
     * @return array
     */
    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
            ->select('b.tags')
            ->getQuery()
            ->getResult();

        $tags = array();
        foreach ($blogTags as $blogTag)
        {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }

    /**
     * se sert ensuite du tableau de tafs pour calculer le poids (weight) de chaque tag à partir de son nombre
     * d’occurences dans le tableau. Les tags sont également mélangés afin d’ajouter un peu d’aléatoire à leur affichage.
     *
     * @param $tags
     * @return array
     */
    public function getTagWeights($tags)
    {
        $tagWeights = array();
        if (empty($tags))
            return $tagWeights;

        foreach ($tags as $tag)
        {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }
        // Shuffle the tags
        uksort($tagWeights, function() {
            return rand() > rand();
        });

        $max = max($tagWeights);

        // Max of 5 weights
        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as &$tag)
        {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }

}
