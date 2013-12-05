<?php
error_reporting(E_ALL) ;
set_time_limit(0) ;

# Wait for incoming connections on random port
for ($nr_retries = 0 ; $nr_retries < 5 ; $nr_retries++)
{ $server_port = mt_rand(16384, 49152) ;
  $sock = socket_create_listen($server_port) ;
  if ($sock !== false) break ;
}
if ($sock === false)
{ die('Failed to find unused port'."\n") ;
}
file_put_contents(__FILE__.'.port', $server_port) ;

# echo 'Waiting for incoming connections on port '.$server_port."\n" ;
while ($c = socket_accept($sock))
{ socket_getpeername($c, $addr, $port) ;
  # echo 'Accepted connection from '.$addr.':'.$port."\n" ;
  while ($line = @socket_read($c, 65536, PHP_NORMAL_READ))
  { $line = trim($line) ;
    # echo '['.$line.']'."\n" ;
    if (substr($line, 0, 6) == 'EDIT: ')
    { exec('edit '.substr($line, 6)) ;
    } else
    { echo "\n".'Unknown command: '.$line."\n" ;
    }
  }
  socket_close($c) ;
  # echo 'Closed connection with '.$addr.':'.$port."\n" ;
}
socket_close($sock) ;
# echo 'Edit server done.'."\n" ;

