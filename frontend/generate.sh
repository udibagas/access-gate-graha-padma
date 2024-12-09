#! /bin/bash

npm run generate
cp dist/* ../backend/public/ -r
