#!/bin/bash
shopt -s globstar

mv html dist

sed -i 's#(DEV)##' 'dist/index.html'
sed -i "s#(VERSION)#${1:1}#" 'dist/index.html'