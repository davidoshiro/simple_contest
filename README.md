# Simple Contest

## Installation

### Database

To setup the MySQL database, simply run `create_db.sql`. This will create the `simple_contest` database that contains two tables: 

* contests - Contest details
* contestants - Contestant submissions

The `contestants` table is linked to the `contests` table via `contestants.contest_id -> contests.id`.

### Styles

Simple Contest styles are based on Twitter Bootstrap. All css files are located in the css folder. Use the `bootstrap-tweaks.css` file to override the default bootstrap styles.

The header image is located in the `img` folder. To change the image, simply replace the image `banner_image.jpg` with your own image. Make sure the file name is `banner_image.jpg` or you will have to change the image name referenced in the `css/bootstrap-tweaks.css` file.

