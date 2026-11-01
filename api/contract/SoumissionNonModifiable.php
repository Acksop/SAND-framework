<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Contract;

/**
 * Levée par un dépôt quand la soumission est déjà publiée et non forcée :
 * la route répond 409 (code post_not_editable).
 */
class SoumissionNonModifiable extends \RuntimeException
{
}
