<div>
    @if (session()->has('message'))
        <div class="relative flex flex-col sm:flex-row sm:items-center bg-white shadow rounded-md py-5 pl-6 pr-8 sm:pr-6">
            <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                <div class="text-green-500">
                    <svg class="w-6 sm:w-5 h-6 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">{{session('message')}}</div>
        </div>
    @endif
    <div class="grid lg:grid-cols-5 sm:grid-cols-3 grip-cols-1 gap-4 mt-10  mx-10 lg:mx-auto"  x-data style="max-width: 1250px;">
        <a href="#" x-on:click="$wire.openDialog()">
            <div class="flex bg-gray-800 h-full text-white" >
                <div class="m-auto">
                    Add product
                </div>
            </div>
        </a>
        <x-jet-dialog-modal maxWidth="lg" wire:model="updateMode" >
                <x-slot name="title">
                     @if($product_id == null)
                        {{ __('Add a new product') }}
                     @else
                         @auth
                            {{ __('Update product') }}
                        @else
                             {{__('Details du produit')}}
                        @endauth
                    @endif
                </x-slot>

                <x-slot name="content">
                    @if ($image)
                        Photo Preview:
                        <img src="{{ $image->temporaryUrl() }}">
                    @elseif($image_url)
                        Photo Preview:
                        <img src="{{ $image_url }}">
                    @endif
                    @auth
                    <div class="flex w-full  items-center justify-center bg-grey-lighter">
                        <label class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-white">
                            <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                            </svg>
                            <span class="mt-2 text-base leading-normal">Select an image</span>
                            <input type='file' class="hidden" wire:model="image" />
                        </label>
                    </div>

                    <x-jet-input-error for="image" class="mt-2" />
                    <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-jet-input type="text" class="mt-1 block w-full"
                                     placeholder="{{ __('Name') }}"
                                     x-ref="name"
                                     wire:model.defer="name"
                                     />
                        <x-jet-input-error for="name" class="mt-2" />
                        <textarea class = 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full'
                                   placeholder="{{ __('Description') }}"
                                   x-ref="description"
                                   wire:model.defer="description"
                        >
                        </textarea>
                        <x-jet-input-error for="description" class="mt-2" />

                         <x-jet-input type="number" class="mt-1 block w-full"
                                      placeholder="{{ __('Price') }}"
                                      x-ref="price"
                                      wire:model.defer="price"
                         />
                        <x-jet-input-error for="price" class="mt-2" />
                    </div>
                    @else
                        <p class="font-bold">Name</p>
                        <p>{{$name}}</p>
                        <p class="font-bold">Description</p>
                        <p>{{$description}}</p>
                        <p class="font-bold">Price</p>
                        <p>{{$price}}</p>
                    @endauth
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="cancel" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>
                    @if($product_id ==null)
                        <x-jet-secondary-button class="ml-2 text-white" style="color:white; background-color:blueviolet"  wire:click="store" wire:loading.attr="disabled">
                            {{ __('Create product') }}
                        </x-jet-secondary-button>
                    @else
                        <x-jet-danger-button class="ml-2"  wire:click="showConfirmDelete" wire:loading.attr="disabled">
                            {{ __('Delete product') }}
                        </x-jet-danger-button>
                        <x-jet-dialog-modal maxWidth="sm" wire:model="deleteDialog" >
                            <x-slot name="title">
                                    {{ __('Confirm delete product') }}
                            </x-slot>

                            <x-slot name="content">
                              <p>  {{__('Are you sure to delete this product ?')}}</p>
                            </x-slot>

                            <x-slot name="footer">
                                <x-jet-secondary-button wire:click="closeConfrimdelete" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-jet-secondary-button>
                                <x-jet-danger-button class="ml-2"  wire:click="delete" wire:loading.attr="disabled">
                                    {{ __('Continue') }}
                                </x-jet-danger-button>
                            </x-slot>
                        </x-jet-dialog-modal>
                        <x-jet-secondary-button class="ml-2 " style="color:white; background-color:blueviolet"  wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update product') }}
                        </x-jet-secondary-button>
                    @endif
                </x-slot>
            </x-jet-dialog-modal>

        @foreach($products as $product)
            <a href="#" x-on:click="$wire.edit({{$product->id}})">
                <div class="flex flex-col border-1 border-gray-500">
                    <div class="flex justify-center">
                        <img src="{{$product->image ?? 'https://business.bridgeafrica.info/assets/img/portfolio/media2.png'}}" alt="">
                    </div>
                    <div>
                        <p class="text-center">{{$product->name}}</p>
                    </div>
                    <div>
                        <p class="normal-nums text-center">
                            {{$product->price}}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
