<?php

/*
 * |--------------------------------------------------------------------------
 * | Register The Artisan Commands
 * |--------------------------------------------------------------------------
 * |
 * | Each available Artisan command must be registered with the console so
 * | that it is available to be called. We'll register every command so
 * | the console gets access to each of the command object instances.
 * |
 */

// Artisan::add(new importFromNostra);
Artisan::add ( new importFromNostraV2 () );
Artisan::add ( new cleanTempFiles () );
Artisan::add ( new TransactionalMigrateCommand  ( app('migrator'), app('path.base').'/vendor' ));
Artisan::add ( new TransactionalRollbackCommand ( app('migrator') ));
