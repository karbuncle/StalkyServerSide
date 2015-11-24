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
    			'access_token' => 'CAANxxasX2V4BAL0sFmBtGaw3QDFzeXrJEsxss2r6mX2oO41Q6nUtpED6VQyZBMEZC8JfRHXGVdZAmCdZADavisXXQ9w5Ajy4rAZBvZAyF6FoyBf8xqGBRIPUpnQOX1CgDGbIU3bFzxd2rlGM9cfqUP5wgOdtXZCZAi0inAgjZC8ZBR0kJ0FVnOPzgMNsPTtytenl85ZCTVIsV0ZCktuI3NjNrtnG'
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
