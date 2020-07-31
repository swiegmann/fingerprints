# Fingerprints


## Overview

Adds md5 fingerprint-hashes (based on PHP filemtime) of selected files and provides the results in the param-pool.


## Installation

1. Copy to extensions-folder.
2. Enable it.


## Usage

Add the files in the system preferences field "Fingerprints".
It accepts one entry per row.

The syntax is:

    nodename1:path/filename1
    nodename2:path/filename2


Example:

    css:workspace/assets/css/app.css
    css-dev:workspace/assets/css/app-dev.css
    js:workspace/assets/js/app.js
    js-dev:workspace/assets/js/app-dev.js


You can now access the fingerprint-hashes in the param-pool.
The result of the above example will be:

    <params>
        ...
        <fingerprint-css>421b61b1e1c65b1205bee2721bbca6d7</fingerprint-css>
        <fingerprint-css-dev>6112608910dc88bb733369e143e01290</fingerprint-css-dev>
        <fingerprint-js>01c2fabdfba6411a05174b5638a833f6</fingerprint-js>
        <fingerprint-js-dev>6112608910dc88bb733369e143e01290</fingerprint-js-dev>
        ...
    </params>
