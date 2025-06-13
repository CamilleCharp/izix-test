#! /bin/bash


export API_NGINX_PORT=9876
export SIMULATOR_NGINX_PORT=6543
export TEST_API_DB=izix
export TEST_API_USER=api
export TEST_API_PASSWORD=api
export TEST_POSTGRES_PASSWORD=root
export SIMULATOR_API_KEY=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
export FAKE_API_KEY=bTksWRZoRzsfsHWOwnYsMSjFfIoMVhHWPYZDVRAkrxdGipIcjfnOzxPfenWZXpSv

export API_URL=http://app-web
export SIMULATOR_URL=http://simulator-web


cd app/
echo "Running tests locally before deployments"

app_output=$(php artisan test)

echo "Tests :"
echo "$app_output"

if echo "$app_output" | grep -q "FAILURES\|ERRORS\|FAIL"; then
    echo "Some API tests failed, aborting deployment."
    exit 1
else
    echo "All API tests passed."
fi

cd ../simulator

simulator_output=$(php artisan test)

echo "Tests :"
echo "$simulator_output"

if echo "$simulator_output" | grep -q "FAILURES\|ERRORS\|FAIL"; then
    echo "Some simulator tests failed, aborting deployment."
    exit 1
else
    echo "All simulator tests passed."
fi

cd ../

podman compose up --build -d