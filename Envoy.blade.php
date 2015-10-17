@servers([ 'production' => 'ubuntu@54.149.222.140' ])

@task( 'deploy', [ 'on' => 'production', 'confirm' => true ]  )
	cd /var/www/stalky
	git pull origin master
	php artisan migrate
@endtask
