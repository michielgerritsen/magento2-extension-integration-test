This directory holds 2 files: `integration-test.yml` and `unit-test.yml`. When committing this to your repository both will be run in parallel.

Both files are provided with commentary on each command about what they do. Please read them carefully as you need to replace the `<VENDOR>` and `<MODULE>` placeholders.

This files could be merged, but this way the tests are run in parallel, so your tests will finish faster. You need to place the files in `~/.github/workflows/`. After comitting and pushing the Github Actions will be triggered automatically. 