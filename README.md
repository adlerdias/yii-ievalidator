yii-ievalidator
===============
https://github.com/adlersd/yii-ievalidator/

Validador de Inscrição Estadual

What's that
-----------

yii-ievalidator is an extension for Yii Framework, for automate the IE validation from brazil.

Usage
-----

  inscricaoEstadual = inscricaoEstadual value;
  estado = estado value; // Two Characters


class example extends CModel {
    //[....]
    public function rules()
    {
        return array(
            //[....]
            array('inscricaoEstadual', 'ext.validators.IeValidator', 'with'=>'estado'),
        );
    }
}