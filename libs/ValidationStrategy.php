<?

abstract class ValidationStrategy {

	protected $chain;

	public function __construct($chain = NULL) {
	}
	
	public abstract function validate($value);
	
}

?>
