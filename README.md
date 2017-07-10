# AndroidDrawableResizer

Automatic resizing of Android drawables from one density to all others

## Requirements

 * Windows **or** PHP
 * ImageMagick


## How to use (PHP)

##### Options:
* -i input directory, if not set, will use current working directory.
* -f one file input, instead of a whole directory, can not be used with -i option.
* -o output directory (optional), if not set, will use current working directory.
* -d current density of input images (can be: ldpi, mdpi, hdpi, xhdpi, xxhdpi, xxxhdpi), if not set, will prompt a question.
* -r replace current drawables folders if exists.
* -h show help message.

##### Examples:
* A whole images directory:
  `php AndroidDrawableResizer.php -i=~/images -o=~/Desktop/output`
* One file:
  `php AndroidDrawableResizer.php -f=~/images/oneImage.png -o=~/Desktop/output`
* Specify desity and force rewriting on old files:
  `php AndroidDrawableResizer.php -r -d=mdpi`
* You can also use it without options for current directory:
  `php AndroidDrawableResizer.php`


## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
