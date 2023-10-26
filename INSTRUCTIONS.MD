When installing this plugin on any past sites, do the following:

1. Go to functions.php in the theme and remove the dependencies that are being loaded from this plugin. Should be everything except the stylesheet's CSS.

2. Go to the Kaleidico testimonials plugin and remove the Slick dependencies. Add slickjs to the javascript array.