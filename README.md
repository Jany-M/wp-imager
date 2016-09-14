# WP Imager `v 2.7.1`

**WP Imager** makes image management easier when it comes to manipulating, caching and customizing WordPress images.
It has a pure PHP WP twin, [PHP-Imager](https://github.com/Jany-M/php-imager)

Can be used inside or outside the loop:

- If used inside a loop, the script will automatically retrieve an image from the post, following a priority pattern: featured image, random image attached to the post, first image found in post even if external or not attached to post.
- If used outside the loop, for any image you want display in the template, use the `$exturl` param.
- If used outside the loop, to display images based on the Post ID, then use the param `$post_id`.

> Uses **[TimThumb](http://code.google.com/p/timthumb/)** for image resizing and caching

> **[WPML](https://wpml.org/)** 100% compatible

> Supports **WordPress [Jetpack](http://jetpack.me/)'s [Photon](https://developer.wordpress.com/docs/photon/api/) module**, it automatically switches to Photon cached images' URLs, if module is on, while keeping your custom sizes and with no need to tweak or edit a thing

> Caches images outside of the WordPress folders, to avoid clutter and overflowing upload folders


## To-Do

- Convert function params to array
- Add TimThumb filters
- Support for [Jetpack](http://jetpack.me/)'s [Photon](https://developer.wordpress.com/docs/photon/api/) filters. Maybe?


## Requirements

- PHP 5.2.x or higher
- GD image library

## Get started

1. Place the provided `cache_img` folder in your site's root folder.

> Make sure that `cache_img/cache` is writable, in case images are not displaying.

2. Place the `wp-imager.php` file in your WP template.

3. Call `wp-imager.php` from your functions.php

```php
<?php include 'wp-imager.php'; ?>
```

> If you want to have pretty img urls, then there's an extra step:
> - If you don't have an `.htaccess` yet, place the one provided in your site's root folder.
> - If you already have an `.htaccess`, then adapt it, following the one provided.

**If you don't complete every step the script won't work.**

## Parameters

```php
<?php wp_imager($width=null, $height=null, $crop=null, $class='', $link=false, $exturl=null, $nohtml=false, $post_id=null, $bg_color=null); ?>
```

<table>
  <tr>
    <th>Parameter</th>
    <th>Type</th>
    <th>Description & Options</th>
    <th>Default</th>
  </tr>
  <tr>
    <td><code>width</code></td>
    <td>int</td>
    <td>Resize dimension of width (dont put 'px' after size)</td>
    <td>100</td>
  </tr>
  <tr>
    <td><code>height</code></td>
    <td>int</td>
    <td>Resize dimension of height (dont put 'px' after size)</td>
    <td>100</td>
  </tr>
  <tr>
    <td><code>crop</code></td>
    <td>int</td>
    <td>Type of cropping to perform
    0 = Resize to Fit exactly specified dimensions (no cropping)
    1 =	Crop and resize to best fit the dimensions (default)
    2 =	Resize proportionally to fit entire image into specified dimensions, and add borders if required
    3 =	Resize proportionally adjusting size of scaled image so there are no borders gaps</td>
    <td>1</td>
  </tr>
  <tr>
    <td><code>class</code></td>
    <td>string</td>
    <td>class name/names to append to image</td>
    <td>NULL</td>
  </tr>
  <tr>
    <td><code>link</code></td>
    <td>bool</td>
    <td>Wraps the image in HTML, pointing to the image's post, with title attribute filled with post's title for better SEO. Won't work with <code>$exturl</code></td>
    <td>false</td>
  </tr>
  <tr>
    <td><code>exturl</code></td>
    <td>string</td>
    <td>URL of some external/custom image (eg. http://www.mysite.com/image.jpg)	</td>
    <td>NULL</td>
  </tr>
  <tr>
    <td><code>nohtml</code></td>
    <td>bool</td>
    <td>When false, image is wrapped in its <code>img</code> HTML tag, with <code>alt</code> attribute filled with post's title for better SEO. If true, only the image url is returned</td>
    <td>false</td>
  </tr>
  <tr>
    <td><code>post_id</code></td>
    <td>int</td>
    <td>If empty, will retrieve <code>$post->ID</code> from active loop, else specify the post ID you need to retrieve the img from</td>
    <td><code>$post->ID</code></td>
  </tr>
  <tr>
    <td><code>bg_color</code></td>
    <td>int</td>
    <td>When using crop value '2' (with borders) you can customize the borders color (the canvas beneath the image).</td>
    <td>ffffff</td>
  </tr>
</table>


## Defaults

- Function always returns to avoid yet another parameter, so simply echo it in your code.
- Processed IMG's quality is always 100
- Caching is done in a cache_img folder, in the root of your website (provided)
- Pretty img urls are enabled by default. Adapt the .htaccess provided with the script.

### Usage

## Resize + default Cropping

```php
<?php echo wp_imager(600, 350); ?>
```

## Resize with no Cropping

```php
<?php echo wp_imager(600, 350, 0); ?>
```

## Resize + default Cropping + Image Class

```php
<?php echo wp_imager(600, 350, '', 'img-responsive'); ?>
```

## Resize + default Cropping + WP post link

```php
<?php echo wp_imager(600, 350, '', '', true); ?>
```

## Resize + default Cropping + custom img URL (outside the loop)

```php
<?php echo wp_imager(600, 350, '', '', '', 'http://www.domain.com/image.jpg'); ?>
```

## Resize + default Cropping + no html wrapper

```php
<?php echo wp_imager(600, 350, '', '', '', '', true); ?>
```

### Conflicting Params

Clearly there are some parameters you cannot use together and as the script is specifically made to use within WordPress, restrictions may apply.

For example:
- <code>$link = true</code> and <code>$nohtml = true</code> dont make sense together, as you can imagine (<code>$nohtml = true</code> wins anyway)
- <code>$class</code> won't do anything if <code>$nohtml = true</code>
- <code>$link = true</code> and <code>$exturl</code> will output a broken or empty post url, if <code>$exturl</code> is not a post image (yes, <code>$exturl</code> should work also for images within WordPress but I haven't tested it yet)


## Changelog

**30/4/2016**
- version 2.6.5
- Fixed minor issues with WPML compatibility
- Overriding `jpeg_quality` and `wp_editor_set_quality` to 100
- Added `post_id` param support (in previous versions)

**10/12/2015**
- version 2.6
- Added support in case there's no attachments in posts but there are images within the content
- Added support in case after content import, attachments ID are incorrect, the script will still find the image provided it's in the current server (with same folder structure)

**14/9/2016**
- version 2.7.1
- Fixed minor bugs
- Added 100 quality on jpg
- Preparing for params as array
- Preparing for TimThumb Filters

**22/7/2015**
- version 2.5
- Updated Timthumb to latest available (June 2014)
- Change TimThumb default from timthumb-config.php
- Added canvas color param
- Updated htaccess rule
- Various small improvements

**6/7/2015**
- version 2.2
- Support for Post ID (outside of loop)

**7/4/2015**
- version 1.8
- Fixes to WPML compatibility bug
- Support for Photon

**23/3/2015**
- version 1.5
- Fixes to WPML compatibility

**2/12/2014**
- added compatibility with WPML

**3/2/2014**
- added $nohtml param
- added $htaccess var
- fixed various bugs

**29/1/2014**
- `release` version 1.0

## Credits

TimThumbs (discontinued): [BinaryMoon](http://code.google.com/p/timthumb/)

## Author

Jany Martelli @ [Shambix](http://www.shambix.com)

## License

Released under the [GPL v3 License](http://choosealicense.com/licenses/gpl-v3/)
