<?php

use Illuminate\Database\Seeder;
use App\FacebookUtil;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$params = [
    			'limit' => 50,
    			// thats my freaking token, dont know when it will expire though.
    			'access_token' => 'CAANxxasX2V4BAKHo1z2BhghfILZBgiB7tkcyYsH9hug0nNZBZCZAm0nQ7mvB5So0otq10BbvJnCP4NKxIasNimPRo1ixjzTy0i3D1q04zLelbVn0TkL01uqH5opcZBQvqqAmTAkjgf00tG6LFjM2SBsOjN1WuqXbZAMfO5ZAqj9DQVnKbWy4fCDhu7bRwYf7KtmlcZBZC3oLRLHlQnKC6Mqhg'
    	];
    	$response = FacebookUtil::getInstance()->rawGraphRequest( 'GET', '331733603546959/members', $params );
    	$members = json_decode( $response->getBody()->getContents() );
        for( $offset = 50; !empty( $members->data ); $offset += 50 ) {
        	foreach( $members->data as $member ) {
        		DB::table('users')->insert([
        				'name' => $member->name,
        				'facebook_id' => $member->id,
        		]);
        	}
        	$params[ 'offset' ] = $offset;
        	$response = FacebookUtil::getInstance()->rawGraphRequest( 'GET', '331733603546959/members', $params );
        	$members = json_decode( $response->getBody()->getContents() );
        }
    }
}
