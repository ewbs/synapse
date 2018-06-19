<?php
class IdeasSeeder extends Seeder {
	public function run() {
		$ideas = array (
				array ( // 1
						'user_id' => 1, // julian
						'name' => 'Idée de simplification de test',
						'description' => 'Cette idée de simplification a été générée automatiquement par le gestionnaire de DB de Synapse afin de tester l\'insertion de données dans la base.',
						'ewbs_member_id' => 2,
						'ext_contact' => 'Cathy Penneflamme',
						'abc_notrelated' => 'Ce champs est rempli automatiquement',
						'freeencoding_nostra_publics' => 'test p',
						'freeencoding_nostra_thematiquesabc' => 'test t abc',
						'freeencoding_nostra_thematiquesadm' => 'test t adm',
						'freeencoding_nostra_evenements' => 'test e',
						'freeencoding_nostra_demarches' => 'test d',
						'doc_source_title' => 'test document source : titre',
						'doc_source_page' => '144, alinéa 8',
						'doc_source_link' => 'http://www.perdu.com',
						'prioritary' => 0,
						'transversal' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'ideas' )->insert ( $ideas );
		
		// ETATS D'UNE IDEE
		$states = array (
				array (
						'name' => 'ENCODEE',
						'order' => 10,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'REVUE',
						'order' => 20,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'VALIDEE',
						'order' => 30,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'ENREALISATION',
						'order' => 40,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'REALISEE',
						'order' => 50,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'SUSPENDUE',
						'order' => 60,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'name' => 'ABANDONNEE',
						'order' => 70,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'ideaStates' )->insert ( $states );
		
		// relation "idée à une ou plusieurs administrations"
		$ideas = array (
				array (
						'idea_id' => 1,
						'administration_id' => 1 
				),
				array (
						'idea_id' => 1,
						'administration_id' => 10 
				) 
		);
		DB::table ( 'administration_idea' )->insert ( $ideas );
		
		// relation "idée à une ou plusieurs ministres compétents"
		$ideas = array (
				array (
						'idea_id' => 1,
						'minister_id' => 1 
				),
				array (
						'idea_id' => 1,
						'minister_id' => 10 
				) 
		);
		DB::table ( 'idea_minister' )->insert ( $ideas );
		
		// une idée a des modifications de status
		$states = array (
				array (
						'idea_id' => 1,
						'idea_state_id' => 1, // ENCODEE
						'user_id' => 1, // Julian
						'comment' => 'Etat par défaut encodé par Synapse',
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "01/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "01/10/2015" ) 
				),
				array (
						'idea_id' => 1,
						'idea_state_id' => 2, // REVUE
						'user_id' => 1, // Julian
						'comment' => 'Passage en revue côté eWBS',
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "05/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "05/10/2015" ) 
				),
				array (
						'idea_id' => 1,
						'idea_state_id' => 3, // VALIDEE
						'user_id' => 1, // Julian
						'comment' => 'A été validée par l\'administration :-)',
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "10/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "10/10/2015" ) 
				) 
		);
		
		DB::table ( 'ideaStateModifications' )->insert ( $states );
		
		// relation : commentaires
		
		$comments = array (
				array (
						'user_id' => 1, // Julian
						'idea_id' => 1,
						'comment' => "Ceci est mon premier commentaire :-) bla bla bla",
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "02/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "02/10/2015" ) 
				),
				array (
						'user_id' => 2, // Elo
						'idea_id' => 1,
						'comment' => "Et moi je répond",
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "03/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "03/10/2015" ) 
				),
				array (
						'user_id' => 1, // Julian
						'idea_id' => 1,
						'comment' => "Et je re-répond !",
						'created_at' => DateTime::createFromFormat ( "d/m/Y", "09/10/2015" ),
						'updated_at' => DateTime::createFromFormat ( "d/m/Y", "09/10/2015" ) 
				) 
		);
		
		DB::table ( 'ideaComments' )->insert ( $comments );
		
		/*
		 * //relation "idée a un ou plusieurs publics cibles
		 * $ideas = array( array('idea_id'=>1, 'nostra_public_id'=>2), array('idea_id'=>1, 'nostra_public_id'=>5) );
		 * DB::table('idea_nostra_public')->insert( $ideas );
		 * //relation "idée a un ou plusieurs thematiques
		 * $ideas = array( array('idea_id'=>1, 'nostra_thematique_id'=>8), array('idea_id'=>1, 'nostra_thematique_id'=>9) );
		 * DB::table('idea_nostra_thematique')->insert( $ideas );
		 * //relation "idée a un ou plusieurs evenements
		 * $ideas = array( array('idea_id'=>1, 'nostra_evenement_id'=>20), array('idea_id'=>1, 'nostra_evenement_id'=>18) );
		 * DB::table('idea_nostra_evenement')->insert( $ideas );
		 * //relation "idée a un ou plusieurs demarches
		 * $ideas = array( array('idea_id'=>1, 'nostra_demarche_id'=>335), array('idea_id'=>1, 'nostra_demarche_id'=>484) );
		 * DB::table('idea_nostra_demarche')->insert( $ideas );
		 */
	}
}
