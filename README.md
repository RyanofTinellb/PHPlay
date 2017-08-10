# PHPlay

This repository represents my knowledge of PHP and MySQL.

These files all use a SQL table holding my episode list, the ~6000 items I currently have on DVD.

**index.php** - As I am currently watching everything in a particular order, this displays information about the current episode. This includes the name of the episode and where I can find it, the airdate, what other shows were airing around the same time, and how long until I finish the list.

**next.php** - This is called by _index.php_, and loads up the next episode.

**eplist.php** - This is a work in progress. This allows for a user to display a particular season of a television show.

**randomepisode.php** - This displays a list of random episodes, each with a link to its Google search.

**randomtrek.php** - This is similar to _randomepisode.php_, but the episodes all come from Star Trek, and links to Memory Alpha, the Star Trek Wiki.

**randomscrawl.php** - Not very useful, looks like a late &rsquo;90s webpage, but is exactly the same as _randomtrek.php_, except for the starfield and the marquee ship. Or should that be 'Maquis'?
