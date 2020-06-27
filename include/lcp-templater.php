<?php
/**
 * Defines the LcpTemplater class
 */

/**
 * Contains and manages all template-related logic.
 *
 * This class is used to choose the correct template file,
 * either the default one or one that corresponds to the `template` parameter
 * supplied by the user.
 *
 * It also stores and manages:
 *
 * - the list's outer tag
 * - the list's inner tags
 */
class LcpTemplater {

  /**
   * Paths to template directory.
   *
   * @var array
   */
  private static $paths = null;

  /**
   * Path to the template file being used.
   *
   * @var string
   */
  private $template_file;

  /**
   * List's outer tag.
   *
   * @var string
   */
  public $outer_tag = null;

  /**
   * List's inner tag.
   *
   * @var null
   */
  public $inner_tag = null;

  /**
   * Instance constructor.
   *
   * Sets the proper template to be used. Either the default one or one
   * specified by the user, if set. If $param is ul, div, or ul this method
   * will also set proper outer and inner tags.
   *
   * @param string $param 'template' shortcode parameter.
   */
  public function __construct($param) {
    // Default plugin template.
    $this->template_file = plugin_dir_path(__DIR__) . 'templates/default.php';

    if (empty($param)) {
      // Use default plugin template.
      ;
    } else if ( preg_match('/^ul$|^div$|^ol$/i', $param, $matches)) {
      // Use default plugin template, set outer and inner tags.
      $this->outer_tag = ($matches[0]);
      if ('div' === $this->outer_tag) {
        $this->inner_tag = 'p';
      }
    } else {
      // Try user's template.
      $this->select_template($param);
    }
  }

  /**
   * Gets the possible template direcotry paths.
   *
   * The paths are stored in the $paths variable and returned.
   *
   * @return array Paths to template directory.
   */
  private static function get_template_paths() {
    if (null === self::$paths) {
      self::$paths = array_unique(
        [
          get_stylesheet_directory()  . '/list-category-posts/',
          get_template_directory() . '/list-category-posts/',
        ]
      );
    }
    return self::$paths;
  }

  /**
   * Gets and returns all available template names.
   *
   * THis method scans the template directory and outputs all available
   * template names in an array. This is currently only used by the widget.
   *
   * @return array All available template names (without .php extension).
   */
  public static function get_templates() {
    $templates = [];
    $paths = self::get_template_paths();

    foreach ($paths as $path) {
      foreach (scandir($path) as $file) {
        if (! self::validate_template($path, $file)) {
          continue;
        }

        $template_name = substr($file, 0, strlen($file) - 4);

        // Add the template only if necessary
        if (! in_array( $template_name, $templates)) {
          $templates[] = $template_name;
        }
      }
    }
    return $templates;
  }

  /**
   * Checks whether specified template files are formatted properly.
   *
   * @param  string $path Path to the template directory.
   * @param  string $file Template file name.
   * @return bool         Is the template a proper php file.
   */
  private static function validate_template($path, $file) {
    return (substr($file, -4) == '.php' ) && is_file($path . $file) &&
      is_readable($path . $file);
  }

  /**
   * Determine template path.
   *
   * Checks if the user specified template file exists and if so,
   * sets the $template_file member variable to the template file's path.
   *
   * @param  string $param 'template' shortcode paramter.
   */
  private function select_template($param) {
    // Path to a proper template file.
    $template = null;

    // Get templates paths and  search for the php file.
    $paths = self::get_template_paths();
    foreach($paths as $path) {
      if (self::validate_template($path, $param . '.php')) {
        $template = $path . $param . '.php';
        break;
      }
    }

    if ($template) {
      $this->template_file = $template;
    }
  }

  /**
   * Updates the list's outer tag.
   *
   * @param  string $tag Outer tag, ex. ul.
   */
  public function update_outer_tag($tag) {
    $this->outer_tag = $this->outer_tag ?: $tag;
  }

  /**
   * Updates the list's inner tag.
   *
   * @param  string $tag Inner tag, ex. li.
   */
  public function update_inner_tag($tag) {
    $this->inner_tag = $this->inner_tag ?: $tag;
  }

  /**
   * Getter method for the template path.
   *
   * @return string $template_file value.
   */
  public function get_template() {
    return $this->template_file;
  }
}
