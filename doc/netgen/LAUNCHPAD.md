Netgen Site development with eZ Launchpad and Docker
====================================================

Software requirements
---------------------

* [Docker](https://docs.docker.com)
* [eZ Launchpad](https://ezsystems.github.io/launchpad/)
* [Yarn](https://yarnpkg.com/en/)

Installation instructions
-------------------------

### Docker containers

First create the directory where you want your Media Site project to live and enter it:

```bash
mkdir my-project-with-media-site
cd my-project-with-media-site
```

Instantiate the Media Site with eZ Launchpad:

```bash
ez init netgen/media-site CURRENT_VERSION INSTALL_TYPE
```

where `netgen/media-site` is the name of Media Site package in Composer, `CURRENT_VERSION` is a version string, e.g. `1.1.3` and `INSTALL_TYPE`
is the type of site to install. Currently there are two options: `netgen-media` that contains demo data and `netgen-media-clean` which has no
demo data at all. Now just select `0` for standard installation and wait for Docker to finish creating containers.

When the whole process of creating containers is done, eZ Launchpad will print out a very nice report page with all currently running containers.

### Generate frontend assets

First enter the project directory:

```bash
cd ezplatform
```

Then run the following to generate development versions of the assets:

```
yarn install
yarn build:dev
```

or to build production versions of the assets:

```
yarn install
yarn build:prod
```

Go back to parent directory:

```bash
cd ..
```

### Generate image variations

If using demo content, it can be quite resource intensive to generate all needed image variations
at request time, especially when demo content uses high quality and high resolution images.

To overcome this, you can use the following command to generate all image variations for all images:

```
ez sfrun ngsite:content:generate-image-variations
```

This command will take a couple of minutes to complete, so grab a cup of coffee while it's running.
