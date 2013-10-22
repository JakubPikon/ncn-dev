<?php
 

interface ContextAware { }
interface SessionAware { }
interface ModelAware { }
interface ConfigAware { }

interface Initializable {
	public function init($context);
}