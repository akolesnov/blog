<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $faker;

    private $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->faker = Factory::create();
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function loadPosts(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i ++) {
            $post = new Post();
            $post->setTitle($this->faker->text(100));
            $post->setBody($this->faker->text(1000));
            $post->setSlug($this->slugger->slug($post->getTitle()));
            $post->setCreatedAt($this->faker->dateTime);

            $manager->persist($post);
        }
        $manager->flush();
    }
}
