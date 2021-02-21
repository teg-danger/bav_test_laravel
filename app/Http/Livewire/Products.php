<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Request;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithFileUploads;
    public  $name, $description, $price, $product_id, $image, $image_url;
    public $updateMode = false;
    public $deleteDialog = false;

    /**
     * @var \Illuminate\Support\Collection
     */
    public  $products ;


    public function mount()
    {

    }
    public function render()
    {
        $this->products = Product::latest()->get();
        return view('livewire.products');
    }

    public function openDialog()
    {
        if(Auth::guest()){
            return redirect()->to('/login');
        }
        $this->resetInputFields();
        $this->updateMode = true;

    }
    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->product_id = null;
        $this->image = null;
        $this->image_url = null;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image'
        ]);

        $product = Product::create(array_merge($validatedData, ["user_id" => Auth::id()]));
        $product->image = Storage::url($this->image->store('public/images'));
        $product->save();
        $this->products = Product::latest()->get();
        $this->updateMode= false;
        session()->flash('message', 'Product Created Successfully.');

        $this->resetInputFields();

        $this->emit('userStore'); // Close model to using to jquery

    }

    public function edit($id)
    {
        $this->updateMode = true;
        $product = Product::find($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->image_url = $product->image;

    }

    public function cancel()
    {
        $this->updateMode = false;

    }

    public function update()
    {
        if(Auth::guest()){
            return redirect()->to('/login');
        }
         $this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'image|nullable'
        ]);

        if ($this->product_id) {
            $product = Product::find($this->product_id);
            $product->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
            ]);
            if($this->image != null){
                $product->image = Storage::url($this->image->store('public/images'));
                $product->save();
            }
            $this->products = Product::latest()->get();
            $this->updateMode = false;
            session()->flash('message', 'Product Updated Successfully.');

        }
    }

    public function showConfirmDelete()
    {
        if(Auth::guest()){
            return redirect()->to('/login');
        }
        $this->deleteDialog  = true;
    }
    public function closeConfrimdelete()
    {
        $this->deleteDialog = false;
    }

    public function delete()
    {

        if($this->product_id){
            Product::where('id',$this->product_id)->delete();
            $this->deleteDialog  = false;
            $this->updateMode =false;
            $this->products = Product::latest()->get();
            session()->flash('message', 'Product Deleted Successfully.');
        }
    }

}
