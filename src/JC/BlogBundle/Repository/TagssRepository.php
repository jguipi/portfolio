<?php

namespace JC\BlogBundle\Repository;

/**
 * TagssRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagssRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSelectecTagBlog($tag, $limit = null)
    {

        $selected_tag = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.value = :tag')
            ->addOrderBy('t.id', 'DESC')
            ->setParameter('tag', $tag);

        if (false === is_null($limit))
            $selected_tag->setMaxResults($limit);

        return $selected_tag  ->getQuery()
            ->getResult();


    }

    public function getBlogTag($blog_id, $limit = null)
    {

        $selected_tag = $this->createQueryBuilder('t')
            ->select('t.value')
            ->where('t.blog_id = :blog_id')
            ->setParameter('blog_id', $blog_id);

        if (false === is_null($limit))
            $selected_tag->setMaxResults($limit);

        return $selected_tag  ->getQuery()
            ->getResult();


    }

    /**
     *
     * Prend les tag dans la db dans le format CSV (comma separated values, c’est à dire que chaque valeur est séparée
     * de la précédente par une virgule) séparer et de renvoyer le résultat sous la forme d’un tableau
     *
     * @return array
     */

    public function getAllTag(){
        $selected_tags = $this->createQueryBuilder('t')
            ->select('t.value')
            ->getQuery()
            ->getResult();

        $tags = array();

        foreach ($selected_tags as $selected_tag)
        {
            $tags = array_merge(explode(",", $selected_tag['value']), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }


        return $tags;
    }
}
