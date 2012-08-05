#!/usr/bin/perl -w
# Run through a file of function names
#// For each function, 
#//	grep into the php files in this directory
#//	grep into the lib_todos.php
#//	determine lines that match
#//	output matching lines to func_usg output file
#//	get usage count statistics
use strict;



my $argc = @ARGV;
my $ct = 0;
if($argc==0){&usage()}
my $lib = $ARGV[0];
if (! (-f $lib)){&usage()}


$ct = &genFuncUsg($lib);


##################################
sub genFuncUsg{
	($lib) = @_;
		 my $file_match = '*.php';
		 my $file_funcs = $lib . ".func_list";
                 my $file_out = $lib . ".rpt";
		 my ($func,$line,$php_files,$php_ct,$lib_refs,$lib_ct);
                 my $ding = 0;              #Ring the bell when a file changes
		my $ct=0;
		open (FF, "+>$file_funcs");
		 my $func_list = `grep '^function' $lib`;
		print FF $func_list;
		close FF;
                open (FILE_IN, "+<$file_funcs");
                open (FILE_OUT, "+>$file_out");
                my @all_lines = <FILE_IN>;
                foreach $line (@all_lines)
                {
			$ct++;
			chop($line);
			$func = $line;
			$func =~ s/^function\s*([a-zA-Z0-9_]*).*/$1/;
			if($func){$ding++;}
			print FILE_OUT "###################  \n";
			print FILE_OUT $func;
			print FILE_OUT "\n###################  \n";
			### check for func name in php files in this directory
			##$php_files = `grep -n $func $file_match| grep -v $lib`;
			$php_ct	= `grep -c $func $file_match| grep -v ':0'| grep -v $lib`;
			$lib_refs = `grep -n $func $lib| grep -v 'function'`;
			$lib_ct  = `grep -c $func $lib`;
			chop($lib_ct);
			##print FILE_OUT $php_files;
			print FILE_OUT " \n Found in the following files\n";
			print FILE_OUT "  $php_ct\n";
			print FILE_OUT " Library References: \n";
			print FILE_OUT $lib_refs;
			print FILE_OUT "  found $lib_ct times in $lib \n\n";

                }#end of foreach
                close (FILE_IN);
                close (FILE_OUT);

	return($ct);
}
sub usage{
        print " func_list <library> \n";
        print " \n";
        print "        Report on function usage for library\n";
        print "\n";
        exit;
}


