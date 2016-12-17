<?php
namespace Olc\Widget;

class Tag extends Widget
{
    protected $name = '';

    protected static $voidTagsReverse = array(
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr',
        'img', 'input', 'keygen', 'link', 'meta', 'param', 'source',
        'track', 'wbr', 'basefont', 'bgsound', 'frame', 'isindex'
    );
    protected static $validAttributesReverse = array(
        'accept', 'acceptCharset', 'accessKey', 'action', 'allowFullScreen',
        'allowTransparency', 'alt', 'async', 'autoCapitalize', 'autoComplete',
        'autoCorrect', 'autoFocus', 'autoPlay', 'autoSave', 'capture',
        'cellPadding', 'cellSpacing', 'charSet', 'challenge', 'checked',
        'classID', 'class', 'cols', 'colSpan', 'content', 'contentEditable',
        'contextMenu', 'controls', 'coords', 'crossOrigin', 'data', 'dateTime',
        'defer', 'dir', 'disabled', 'download', 'draggable', 'encType', 'for',
        'form', 'formAction', 'formEncType', 'formMethod', 'formNoValidate',
        'formTarget', 'frameBorder', 'headers', 'height', 'hidden', 'high',
        'href', 'hrefLang', 'httpEquiv', 'icon', 'id', 'inputMode', 'itemProp',
        'itemScope', 'itemType', 'itemRef', 'itemID', 'keyParams', 'keyType',
        'label', 'lang', 'list', 'loop', 'low', 'manifest', 'marginHeight',
        'marginWidth', 'max', 'maxLength', 'media', 'mediaGroup', 'method',
        'min', 'minLength', 'multiple', 'muted', 'name', 'noValidate', 'open',
        'optimum', 'pattern', 'placeholder', 'poster', 'preload', 'property',
        'radioGroup', 'readOnly', 'rel', 'required', 'results', 'role', 'rows',
        'rowSpan', 'sandbox', 'scope', 'scoped', 'scrolling', 'seamless',
        'selected', 'shape', 'size', 'sizes', 'span', 'spellCheck', 'src',
        'srcDoc', 'srcSet', 'start', 'step', 'style', 'summary', 'tabIndex',
        'target', 'title', 'type', 'unselectable', 'useMap', 'value', 'width',
        'wmode', 'wrap'
    );

    protected static $voidTags;
    protected static $validAttributes;

    public function __construct($name, array $attributes = array(), $child = array())
    {
        parent::__construct(
            $attributes,
            array_slice(func_get_args(), 2)
        );
        $this->name = $name;

        if (!self::$voidTags) {
            self::$voidTags = array_flip(self::$voidTagsReverse);
        }
        if (!self::$validAttributes) {
            self::$validAttributes = array_flip(self::$validAttributesReverse);
        }
    }

    public function render()
    {
        $attributes = '';
        foreach ($this->value as $key => $value) {
            if (!isset(self::$validAttributes[$key])
                || $value === null || $value === false || $value === array()
            ) {
                continue;
            }

            if ($value === true) {
                $value = $key;
            } else {
                $value = htmlspecialchars(
                    is_array($value) ? implode(',', $value) : (string) $value,
                    ENT_COMPAT | ENT_HTML5, 'UTF-8'
                );
            }

            $attributes .= " $key=\"$value\"";
        }

        if (!$this->children && isset(self::$voidTags[$this->name])) {
            return "<{$this->name}{$attributes} />";
        }
        return array(
            "<{$this->name}{$attributes}>",
            $this->getChildren(),
            "</{$this->name}>"
        );
    }
}
