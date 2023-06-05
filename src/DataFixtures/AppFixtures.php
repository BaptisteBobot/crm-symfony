<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Création des utilisateurs
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword('azerty');
        $user1->setFirstName('John');
        $user1->setLastName('Doe');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword('azerty');
        $user2->setFirstName('Jane');
        $user2->setLastName('Smith');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('user3@example.com');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPassword('azerty');
        $user3->setFirstName('Robert');
        $user3->setLastName('Johnson');
        $manager->persist($user3);

        // Création des catégories
        $category1 = new Category();
        $category1->setName('Actualités');
        $category1->setCreatedBy($user1);
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Cinéma');
        $category2->setCreatedBy($user2);
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Musique');
        $category3->setCreatedBy($user3);
        $manager->persist($category3);

        // Création des posts
        $post1 = new Post();
        $post1->setContent('Quelles sont les dernières actualités dans le monde de la technologie ?');
        $post1->setCreatedBy($user1);
        $post1->setCategory($category1);
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setContent('Quel est votre film préféré de tous les temps ?');
        $post2->setCreatedBy($user2);
        $post2->setCategory($category2);
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setContent('Quels sont vos artistes musicaux préférés du moment ?');
        $post3->setCreatedBy($user3);
        $post3->setCategory($category3);
        $manager->persist($post3);

        // Ajout de commentaires pour chaque post
        $comment1 = new Comment();
        $comment1->setContent("Je pense que les dernières actualités technologiques sont centrées sur l'intelligence artificielle.");
        $comment1->setCreatedBy($user2);
        $comment1->setPost($post1);
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setContent('Pour moi, mon film préféré de tous les temps est "Le Parrain".');
        $comment2->setCreatedBy($user3);
        $comment2->setPost($post2);
        $manager->persist($comment2);

        $comment3 = new Comment();
        $comment3->setContent('J\'adore écouter Billie Eilish en ce moment. Ses chansons sont incroyables !');
        $comment3->setCreatedBy($user1);
        $comment3->setPost($post3);
        $manager->persist($comment3);

        $manager->flush();
    }
}
