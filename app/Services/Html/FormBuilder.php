<?php namespace App\Services\Html;

class FormBuilder extends \Collective\Html\FormBuilder {

	public function submit($value = null, $options = [])
	{
		return sprintf('
			<div class="form-group %s">
				%s
			</div>',
			empty($options) ? '' : $options[0],
			parent::submit($value, ['class' => 'btn btn-default'])
		);
	}
	
	public function submit_new($value = null, $options = [])
	{
		return sprintf(parent::submit($value, ['class' => 'submit-button']));
	}

	public function destroy($text, $message, $class = null)
	{
		return parent::submit($text, ['class' => 'btn btn-danger btn-block ' . ($class? $class:''), 'onclick' => 'return confirm(\'' . $message . '\')']);
	}

	public function control($type, $colonnes, $nom, $errors, $label = null, $valeur = null, $pop = null, $placeholder = '')
	{
		if($type == 'textarea' && $nom == 'description' || $nom == 'content'){
			$attributes = ['class' => 'form-control ckeditor', 'id' => $nom ,'placeholder' => $placeholder];
		}
		else{
			$attributes = ['class' => 'form-control', 'id' => $nom ,'placeholder' => $placeholder];
		}
		
		return sprintf('
			<div class="form-group %s %s">
				%s
				%s
				%s
				%s
			</div>',
			($colonnes == 0)? '': 'col-lg-' . $colonnes,
			$errors->has($nom) ? 'has-error' : '',
			$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			$pop? '<a href="#" tabindex="0" class="badge pull-right" data-toggle="popover" data-trigger="focus" title="' . $pop[0] .'" data-content="' . $pop[1] . '"><span>?</span></a>' : '',
			call_user_func_array(['Form', $type], ($type == 'password')? [$nom, $attributes] : [$nom, $valeur, $attributes]),
			$errors->first($nom, '<small class="help-block">:message</small>')
		);
	}
	
	public function control_attr($type, $colonnes, $nom, $errors, $label = null, $valeur = null, $attr = null ,$pop = null, $placeholder = '')
	{
		if(!empty($attr)){
			if($type == 'textarea' && $nom == 'description' || $nom == 'content'){
				$attributes = ['class' => 'form-control ckeditor', 'id' => $nom ,'placeholder' => $placeholder,$attr];
			}
			else{
				$attributes = ['class' => 'form-control', 'id' => $nom ,'placeholder' => $placeholder,$attr];
			}
		}
		else{
			if($type == 'textarea' && $nom == 'description' || $nom == 'content'){
				$attributes = ['class' => 'form-control ckeditor', 'id' => $nom ,'placeholder' => $placeholder];
			}
			else{
				$attributes = ['class' => 'form-control', 'id' => $nom ,'placeholder' => $placeholder];
			}
		}
		
		return sprintf('
			<div class="form-group %s %s">
				%s
				%s
				%s
				%s
			</div>',
			($colonnes == 0)? '': 'col-lg-' . $colonnes,
			$errors->has($nom) ? 'has-error' : '',
			$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			$pop? '<a href="#" tabindex="0" class="badge pull-right" data-toggle="popover" data-trigger="focus" title="' . $pop[0] .'" data-content="' . $pop[1] . '"><span>?</span></a>' : '',
			call_user_func_array(['Form', $type], ($type == 'password')? [$nom, $attributes] : [$nom, $valeur, $attributes]),
			$errors->first($nom, '<small class="help-block">:message</small>')
		);
	}
	
	public function control_new($type, $colonnes, $nom, $errors, $label = null, $valeur = null, $pop = null, $placeholder = '')
	{
		$attributes = ['class' => 'form-control', 'id' => $nom ,'placeholder' => $placeholder];
		return sprintf('
			<div class="%s %s">
				%s
				%s
				%s
				%s
			</div>',
			($colonnes == 0)? '': 'col-lg-' . $colonnes,
			$errors->has($nom) ? 'has-error' : '',
			$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			$pop? '<a href="#" tabindex="0" class="badge pull-right" data-toggle="popover" data-trigger="focus" title="' . $pop[0] .'" data-content="' . $pop[1] . '"><span>?</span></a>' : '',
			call_user_func_array(['Form', $type], ($type == 'password')? [$nom, $attributes] : [$nom, $valeur, $attributes]),
			$errors->first($nom, '<small class="help-block">:message</small>')
		);
	}
	
	public function control_stripe_email($type, $colonnes, $nom, $errors, $label = null, $valeur = null, $pop = null, $placeholder = '')
	{
		$attributes = ['class' => 'form-control', 'id' => $nom ,'placeholder' => $placeholder,'readonly'];
		return sprintf('
			<div class="form-group %s %s">
				%s
				%s
				%s
				%s
			</div>',
			($colonnes == 0)? '': 'col-lg-' . $colonnes,
			$errors->has($nom) ? 'has-error' : '',
			$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			$pop? '<a href="#" tabindex="0" class="badge pull-right" data-toggle="popover" data-trigger="focus" title="' . $pop[0] .'" data-content="' . $pop[1] . '"><span>?</span></a>' : '',
			call_user_func_array(['Form', $type], ($type == 'password')? [$nom, $attributes] : [$nom, $valeur, $attributes]),
			$errors->first($nom, '<small class="help-block">:message</small>')
		);
	}
	
	public function control1($type, $colonnes, $nom, $errors, $label = null, $valeur = null, $pop = null, $placeholder = '')
	{
	
		$attributes = ['placeholder' => $label];
		return sprintf('
			<div class="form-group %s %s">
				%s
				%s
			</div>',
			//($colonnes == 0)? '': 'col-lg-' . $colonnes,
			$errors->has($nom) ? 'has-error' : '',
			//$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			$pop? '<a href="#" tabindex="0" class="badge pull-right" data-toggle="popover" data-trigger="focus" title="' . $pop[0] .'" data-content="' . $pop[1] . '"><span>?</span></a>' : '',
			call_user_func_array(['Form', $type], ($type == 'password')? [$nom, $attributes] : [$nom, $valeur, $attributes]),
			$errors->first($nom, '<small class="help-block">:message</small>')
		);
	}

	public function check($name, $label)
	{
		return sprintf('
			<div class="checkbox col-lg-12">
				<label>
					%s%s
				</label>
			</div>',
			parent::checkbox($name),
			$label
		);		
	}

	public function checkHorizontal($name, $label, $value)
	{
		return sprintf('
			<div class="form-group">
				<div class="checkbox">
					<label>
						%s%s
					</label>
				</div>
			</div>',
			parent::checkbox($name, $value),
			$label
		);		
	}
	
	public function checkBoxNew($name, $label, $value,$checked=null)
	{
		return sprintf('
			<div class="form-group">
				<div class="checkbox">
					<label>
						%s%s
					</label>
				</div>
			</div>',
			parent::checkbox($name, $value,$checked),
			$label
		);		
	}

	public function selection($nom, $list = [], $selected = null, $label = null,$options=array('class' => 'form-control'))
	{
	
		return sprintf('
			<div class="form-group" style="width:200px;">
				%s
				%s
			</div>',
			$label ? $this->label($nom, $label, ['class' => 'control-label']) : '',
			parent::select($nom, $list, $selected, $options)
		);
	}

}
