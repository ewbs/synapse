<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 1.0 vers 1.1.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via IdeaSeeder.php
 */


class UpgradeTo11 extends Seeder {

    public function run()
    {
        
        //ETATS D'UNE IDEE
        $states = array(
            array(
                'name'      => 'ENCODEE',
                'order'     => 10,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'REVUE',
                'order'     => 20,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'VALIDEE',
                'order'     => 30,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'ENREALISATION',
                'order'     => 40,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'REALISEE',
                'order'     => 50,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'SUSPENDUE',
                'order'     => 60,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),
            array(
                'name'      => 'ABANDONNEE',
                'order'     => 70,
                'created_at'=> new DateTime,
                'updated_at'=> new DateTime
            ),            
        );
        
        DB::table('ideaStates')->insert( $states );
           
    }

}