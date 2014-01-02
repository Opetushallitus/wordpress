#!/bin/sh

# Install:
# sudo npm install -g less
# sudo npm install -g uglify-js

# Currently not in use, but should be used later.
#uglifyjs js/hide-address-bar.js > js/all_compressed.js
#uglifyjs js/less-1.3.1.min.js >> js/all_compressed.js

lessc less/style.less > css/all.css
lessc -x less/style.less > css/all_compressed.css