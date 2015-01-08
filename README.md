panicmails
==========

server/client project to gather freebsd panics

Very early, but it seems to DTRT for now.

If an email appears in EMAILADDRESS, pull it down via fetchmail
and process it via procmail.  If it matches on Kernel Panics, go ahead and decrypt.

Process unecrypted kernel panic and insert into local sqlite3 db.

Php will rendor all contents of the db at this time as I'm still testing.
-- Generating a key, pdkesh
 * You need a private and public key for the email exchange
 * Use pdkesh to generate them, named panicmail.pub and panicmail.key

-- building a package

I hacked together a port makefile that will embed some dependencies and allow
a local build from the git checkout, theoretically.  This must be done on a machine
with a valid /usr/ports checkout (portsnap or svn co)
 * sudo make clean
 * cp Makefile.server Makefile
 * sudo make install
 * sudo make package
 * work/pkg has your new package

 * sudo make clean
 * cp Makefile.client Makefile
 * sudo make install
 * sudo make package
 * work/pkg has your new package

This will create a local package of this "stuff" that might even work
