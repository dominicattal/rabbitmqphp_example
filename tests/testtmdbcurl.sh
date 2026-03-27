#!/bin/bash

curl --request GET \
     --url 'https://api.themoviedb.org/3/discover/movie?page=1&sort_by=popularity.desc&primary_release_date.gte=2026-03-10&primary_release_date.lte=2026-04-10&with_release_type=3&language=en-US' \
     --header 'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MTdjMGZmYmQ2ZjA3MmMzNmFiYTcwYjExNjc0Y2YzOSIsIm5iZiI6MTc3MjE0MzQwOS4yODQsInN1YiI6IjY5YTBjMzMxYWNmZjZhNWE0NGIyZmZmZSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zkYQy0oPFzLrgATCb6FoDUhL0_Ut7v67ygks3QI7FXg' \
     --header 'accept: application/json'
