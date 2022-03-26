<?php

class Tag
{
    protected string $name;
    protected array $attrs = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function attr(string $name, string $value)
    {
        $this->attrs[$name] = $value;
        return $this;
    }

    public function render(): string
    {
        return '';
    }

    protected function attrsToStr(): string
    {
        $pairs = [];

        foreach ($this->attrs as $name => $value) {
            $pairs[] = "$name=\"$value\"";
        }
        return $attrsStr = implode('', $pairs);
    }

}


class SingleTag extends Tag
{
    public function render(): string
    {
        $attrsStr = $this->attrsToStr();
        return "<{$this->name} $attrsStr>";
    }
}

class PairTag extends Tag
{
    protected array $children = [];

    public function appendChild(Tag $child)
    {
        $this->children[] = $child;
        return $this;
    }

    public function render(): string
    {
        $attrsStr = $this->attrsToStr();
        $childrenHTML = array_map( function (Tag $tag) {
                    return $tag->render();
                },
                $this->children
            );
        $innerHTML = implode('',$childrenHTML);
        /*$innerHTML = '';
        foreach ($this->children as $child){
            $innerHTML .= $child->render();
        }*/

        return "<{$this->name} $attrsStr>$innerHTML</{$this->name}>";
    }
}

$form = (new PairTag('form'))
    ->appendChild((new PairTag('label'))
                      ->appendChild((new SingleTag('img'))->attr('src', 'f1.jpg')
                                        ->attr('alt','f1 not found'))
                      ->appendChild((new SingleTag('input'))->attr('type', 'text')
                                       ->attr('name', 'f1'))
    )
    ->appendChild((new PairTag('label'))
                      ->appendChild((new SingleTag('img'))->attr('src', 'f2.jpg')
                                        ->attr('alt','f2 not found'))
                      ->appendChild((new SingleTag('input'))->attr('type', 'password')
                                       ->attr('name', 'f2')))
    ->appendChild((new SingleTag('input'))->attr('type', 'submit')
                      ->attr('value', 'Send')
)->render();



echo $form;

