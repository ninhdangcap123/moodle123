<?php
class block_mycustomblock extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_mycustomblock');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = 'This is my custom block content!';
        $this->content->footer = 'Footer of my block content';

        return $this->content;
    }
}
