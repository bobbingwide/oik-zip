<?php // (C) Copyright Bobbing Wide 2012-2016

/*
Plugin Name: oik-zip
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-zip
Description: ZIP a WordPress plugin for release
Version: 0.0.1
Author: bobbingwide
Author URI: http://www.oik-plugins.com/author/bobbingwide
Text Domain: oik-zip
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2012-2016 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/



/**
 * Create a .zip file package for a plugin
 * 
 * Syntax: oikwp oik-zip.php plugin version lang 
 * 
 * Run this from the plugins directory ( or above ) where the plugin is located
 * This should work for symlinked plugins! 
 * 
 */
// $argc =  "Server argc ".  $_SERVER['argc'];7
if ( !isset( $argc ) ) {
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
	//echo $argv[1];
} 

if ( $argc < 2 ) {
	echo "Syntax: oikwp oik-zip.php plugin version lang" ;
	echo PHP_EOL;
	echo "e.g. oik oik-zip.php oik v3.0.0-RC1"; 
	echo PHP_EOL;
} else {
	//$phpfile = $argv[0];
	//echo $phpfile;
	//echo PHP_EOL;
	$plugin = $argv[1];
	$version = $argv[2];
	$lang = bw_array_get( $argv, 3, null );
	$filename = "$plugin $version.zip";
	echo "Creating $filename";
	echo PHP_EOL;
	cd2plugins();
	
	//dogitarchive( $plugin, $filename );
  //docontinue( "After creating git archive: $plugin $version" );
	doreadmetxt( $plugin );
	dosetversion( $plugin, $version );
	docontinue( "$plugin $version" );
	doreadmemd( $plugin );
	// We need to find a good time to decide when to do this
	// First we need to ensure that there really is a string change
	// which means a better comparison of the .pot files
	//
	if ( $lang ) {
		dol10n( $plugin, $lang );	// Should be after doreadmemd()
	}
	dolibs( $plugin );
	
	docontinue( "$plugin $version" );
	doadminactivation( $plugin );
	//dolibs( $plugin );
	
	do7zip( $plugin, $filename );
	//dogitarchive( $plugin, $filename );
}

 

/**
 * 
 * Create a .zip file using 7-Zip
 * 
 
 command "C:\Program Files (x86)\7-Zip\7z.exe"

7-Zip 4.65  Copyright (c) 1999-2009 Igor Pavlov  2009-02-03

Usage: 7z <command> [<switches>...] <archive_name> [<file_names>...]
       [<@listfiles...>]

<Commands>
  a: Add files to archive
  b: Benchmark
  d: Delete files from archive
  e: Extract files from archive (without using directory names)
  l: List contents of archive
  t: Test integrity of archive
  u: Update files to archive
  x: eXtract files with full paths
<Switches>
  -ai[r[-|0]]{@listfile|!wildcard}: Include archives
  -ax[r[-|0]]{@listfile|!wildcard}: eXclude archives
  -bd: Disable percentage indicator
  -i[r[-|0]]{@listfile|!wildcard}: Include filenames
  -m{Parameters}: set compression Method
  -o{Directory}: set Output directory
  -p{Password}: set Password
  -r[-|0]: Recurse subdirectories
  -scs{UTF-8 | WIN | DOS}: set charset for list files
  -sfx[{name}]: Create SFX archive
  -si[{name}]: read data from stdin
  -slt: show technical information for l (List) command
  -so: write data to stdout
  -ssc[-]: set sensitive case mode
  -ssw: compress shared files
  -t{Type}: Set type of archive
  -v{Size}[b|k|m|g]: Create volumes
  -u[-][p#][q#][r#][x#][y#][z#][!newArchiveName]: Update options
  -w[{path}]: assign Work directory. Empty path means a temporary directory
  -x[r[-|0]]]{@listfile|!wildcard}: eXclude filenames
  -y: assume Yes on all queries
  
Code Meaning 
0 No error 
1 Warning (Non fatal error(s)). For example, one or more files were locked by some other application, so they were not compressed. 
2 Fatal error 
7 Command line error 
8 Not enough memory for operation 
255 User stopped the process 

  
*/
function do7zip( $plugin, $filename ) {

  cd2plugins();

  $cmd = '"C:\\Program Files\\7-Zip\\7z.exe"';
  $cmd .= " a "; 
  //$cmd .= " -xr!flh0grep.* -xr!.git* -xr!.idea* -xr!screenshot*";
  $cmd .= " -xr!flh0grep.* -xr!.git* -xr!.idea* -xr!working/*";
  $cmd .= ' "';
  $cmd .= $plugin;
  $cmd .= '.zip" ';
  $cmd .= $plugin;
  $output = array();
  $return_var = null;
  echo $cmd;
  echo PHP_EOL;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  print_r( $output );
  if ( file_exists( $filename ) ) { 
    unlink( $filename );
  }
  $renamed = rename( "${plugin}.zip", $filename );

}

/**
 * Create an archive from a git repo
 * 
 * `
 * git archive master --prefix=oik-bwtrace/ --format=zip -o"..\oik-bwtrace v2.0.1.zip"
 * `
 *
 * The archive file is written in the directory above the git repository
 
 * This is supposedly better than using 7-ZIP
 * 1. It works on a committed level of the repository
 * 2. It won't include files that weren't supposed to be delivered
 * 3. It's possible that it can be used for any tagged version
 * 
 */
function dogitarchive( $plugin, $filename ) {
	setcd( "wp-content", "plugins/$plugin" );
	$cmd = "git archive master --prefix=$plugin/ --format=zip -o\"..\\{$filename}\"";
  $output = array();
  $return_var = null;
  echo $cmd;
  echo PHP_EOL;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
}

/**
 * Create a readme.txt file if necessary
 *
 * If the file does not exist we copy one from the current directory
 * which is expected to be wp-content/plugins
 *
 * @param string $plugin - the plugin slug ( aka folder )
 
 */
function doreadmetxt( $plugin ) {
  if ( !file_exists( "${plugin}\\readme.txt" ) ) {
    copy( "readme.txt", "${plugin}\\readme.txt" );
    echo "Created readme.txt for you to edit" . PHP_EOL;   
  }
}   

/**
 * Invoke the text editor to update the plugin version number etc
 *
 * We use Visual SlickEdit hence the "vs" command
 *
 * @param string $plugin - the plugin slug e.g. "oik-batch" 
 * @param string $version 0 the new version e.g. v2.0
 */  
function dosetversion( $plugin, $version ) {
  echo "Set the version to $version" . PHP_EOL;
  $cmd = "vs ${plugin}\\readme.txt ${plugin}\\${plugin}.php" ; 
  $output = array();
  $return_var = null;
  echo $cmd;
  echo PHP_EOL;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  print_r( $output );
}

/**
 * Create the readme.md file from the readme.txt file
 *
 * Use t2m ( php txt2md.php ) to convert the readme.txt file into a README.md file
 * The README.md file is used in GitHub
 * 
 * @param string $plugin   
 */ 
function doreadmemd( $plugin ) {
  $cwd = getcwd();
  echo __FUNCTION__ . $cwd;
  echo PHP_EOL;
  setcd( "wp-content", "plugins/$plugin" );
  docontinue( "in plugin dir" );
  $return_var = null;
  $cmd = "t2m > README.md";
  echo $cmd;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  //setcd( "wp-content", "plugins" );
  cd2plugins();
}

/**
 * Create the localized versions 
 *
 * i18n - internationalization should already have been done
 * l10n - localization is the creation of the localized versions
 *
 * We assume that the localized versions are stored in the languages directory
 * though we could determine this from the readme.txt file anyway
 * 
 * It would be nice if we could deliver French and German versions out of the box
 * but for now we only do bb_BB and en_GB
 *
 * The code here was based in the makepot.bat file in sneak-peek ( dated 2015/05/xx )
 * copied to makeoik.bat which uses makeoik.php rather than makepot.php
 * since this caters for additional i18n functions.
 *
 * Then I found I'd already coded a lot of it last year in l10n.php...
 * which assumes oik-i18n will contain the localized versions of ALL the language
 * files for oik plugins. The theory being that it will load the plugin domain files
 * rather than having to make each plugins do it itself.
 * 
 *
 */
function dol10n( $plugin, $lang ) {
  //setcd( "wp-content", "plugins/oik" );
	//require_once( "oik_boot.inc");
  setcd( "wp-content", "plugins/$plugin" );
	mkcd( "languages" );
	setcd( "wp-content", "plugins/$plugin/languages" );
  docontinue( "in plugin dir plugins/$plugin/languages" );
	oik_require( "l10n.php", "oik-i18n" );
	do_plugin( $plugin, $lang ); 
  cd2plugins();
	
}

/**
 * Prompt to check if the process should be continued
 *
 * This routine does not make any decisions.
 * If you want to stop you just press Ctrl-Break.
 */
if ( !function_exists( 'docontinue' ) ) { 
function docontinue( $plugin ) {
   echo PHP_EOL;
    echo "Continue? $plugin ";
    $stdin = fopen( "php://stdin", "r" );
    $response = fgets( $stdin );
		$response = trim( $response );
    fclose( $stdin );
    return( $response );
}
}

/**
 * Set the position in the directory tree
 * 
 * @param string $locate - the selected target folder such as "themes" or "plugins"
 * @param string $newdir - the selected folder beneath the located folder
 *  
 */
function setcd( $locate, $newdir ) {
  $cd = getowd();
  $cd = str_replace( '\\', "/", $cd );
  $cds = explode( '/', $cd );
  //print_r( $cds );
  //print_r( $locate );
  docontinue( "OK?" );
  
  $i = 0;   
  while ( $cds[$i] <> $locate && ( $i < count( $cds)) ) {
    echo $cds[$i] . PHP_EOL;
    chdir( $cds[$i] . '/' );
    $i++;
  }  
  chdir( $locate );
  chdir( $newdir );
  echo getcwd();
}

/**
 * Get the original working directory 
 *
 * 
 * Just in case a target directory happens to be a symlinked directory
 * such that getcwd() would not return the right path structure.
 *
 */
function getowd() {
  static $owd = null;
  if ( !$owd ) {
    $owd = getcwd();
  } 
  return( $owd );
}  
 
 
/**
 * Locate the plugins folder
 * 
 * This is NOT expected to be symlinked
 * but individual plugins might be.
 * 
 */
function cd2plugins() {
  setcd( "wp-content", "plugins" );
}

/**
 * make a directory called languages if it doesn't exist
 */
function mkcd( $dir="languages" ) {
	$created = false;
  if ( !is_dir( $dir ) ) {
    $created = mkdir( $dir );
	}
}

   
/*
function phpzip() {

   if ( $cd ) {
     $zip = new ZipArchive();
     $opened = $zip->open( $filename, ZIPARCHIVE::CREATE);
     if ( $opened ) {
       add_files( $zip );
     } else { 
     
         exit("cannot open <$filename>\n");
     }
     echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
     
     $zip->close();
}     

function add_files( $zip ) {
  //$zip->addfile( "readme.txt"

}
*/

	 
/**
 * Update the admin/oik-activation.php to match the latest from oik
 *
 * If the admin folder exists and it contains "oik-activation.php"
 * then copy the latest from oik, if it's different.
 *
 * Now copying the latest version from oik/libs 
 * 
 *  
 */	 
function doadminactivation( $plugin ) {
  if ( $plugin != "oik" ) {
    setcd( "wp-content", "plugins/$plugin" );
		if ( is_dir( "admin" ) ) {
		  if ( file_exists( "admin/oik-activation.php" ) ) {
				echo PHP_EOL;
			  echo "admin/oik-activation.php copy time?";
				copy( "../oik/libs/oik-activation.php", "admin/oik-activation.php" );
			} else {
				echo "No admin activation required?";
			} 
		}
	}
}

/**
 * Ensure the shared library files are up to date
 *
 * @param string $plugin
 */
function dolibs( $plugin ) {
  setcd( "wp-content", "plugins/$plugin" );
	if ( is_dir( "libs" ) ) {
		setcd( "wp-content", "plugins/$plugin/libs" );
		docontinue( "in libs dir plugins/$plugin/libs" );
		oik_require( "libs/oik-libs.php", "oik-libs" );
		oik_libs_compare( $plugin ); 
	}
  cd2plugins();


}


 
   
 
   
   
 
