<template>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area mr-1"></i>Product Management
                        <button class="btn btn-primary btn-sm" v-on:click="showNewProductModal"><span class="fa fa-plus"></span>Create New</button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Name</td>
                                <td>Category</td>
                                <td>Image</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(product, index) in products" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ product.name }}</td>
                                <td>{{findCategory(product.category_id)}}</td>
                                <td>
                                    <img class="table-image" v-bind:src="`${$store.state.serverPath}/storage/${product.image}`" v-bind:alt="product.name">
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" v-on:click="editProduct(product)"><span class="fa fa-edit"></span></button>
                                    <button class="btn btn-danger btn-sm" v-on:click="deleteProduct(product)"><span class="fa fa-trash"></span></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="text-center" v-show="moreExists">
                            <button class="btn btn-sm btn-info" v-on:click="loadMore"><span class="fa fa-arrow-down"></span>Load More</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!--create product-->
        <b-modal ref="newProductModal" hide-footer title="Add Product">
            <div class="d-block text-center">
                <form v-on:submit.prevent="createProduct">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select v-model="productData.category_id" id="category_id" class="form-control">
                            <option value="">Choose Category</option>
                            <option v-for="(category, index) in categories" :value="category.id" :key="index">{{category.name}}</option>
                        </select>
                        <div style="color: red" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label >Enter Name</label>
                        <input type="text" v-model="productData.name" class="form-control" placeholder="Enter Name">
                        <div style="color: red" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="image">Choose Image</label>
                        <div v-if="productData.image.name">
                            <img src="" ref="newProductImageDisplay" style="width: 150px">
                        </div>
                        <input type="file"  class="form-control" id="image" v-on:change="attachImage" ref="newProductImage">
                        <div style="color: red" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-default" v-on:click="hideNewProductModal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span>Save</button>
                </form>
            </div>
        </b-modal>


        <!--edit product-->
        <b-modal ref="editProductModal" hide-footer title="Edit Product">
            <div class="d-block text-center">
                <form v-on:submit.prevent="updateProduct">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select v-model="editProductData.category_id" id="category_id" class="form-control">
                            <option value="">Choose Category</option>
                            <option v-for="(category, index) in categories" :value="category.id" :key="index">{{category.name}}</option>
                        </select>
                        <div style="color: red" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label >Enter Name</label>
                        <input type="text" v-model="editProductData.name" class="form-control" placeholder="Enter Name">
                        <div style="color: red" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="image">Choose Image</label>
                        <div>
                            <img v-bind:src="`${$store.state.serverPath}/storage/${editProductData.image}`" ref="editProductImageDisplay" style="width: 150px">
                        </div>
                        <input type="file"  class="form-control" v-on:change="editAttachImage" ref="editProductImage">
                        <div style="color: red" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-default" v-on:click="hideEditProductModal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span>Update</button>
                </form>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import * as productService from '../services/product_service.js';

    export default {
        name: 'product',
        data(){
            return {
                categories: [],
                products: [],
                productData: {
                    category_id: '',
                    name: '',
                    image: ''
                },
                editProductData: {},
                errors: {},
                moreExists: false,
                nextPage: 0
            }
        },

        mounted(){
            this.loadMore();
            this.loadCategories();
        },

        methods:{
            attachImage(){
                this.productData.image = this.$refs.newProductImage.files[0];
                let reader = new FileReader();
                reader.addEventListener('load', function () {
                    this.$refs.newProductImageDisplay.src = reader.result;
                }.bind(this), false);

                reader.readAsDataURL(this.productData.image);
            },

            editAttachImage(){
                this.editProductData.image = this.$refs.editProductImage.files[0];
                let reader = new FileReader();
                reader.addEventListener('load', function () {
                    this.$refs.editProductImageDisplay.src = reader.result;
                }.bind(this), false);

                reader.readAsDataURL(this.editProductData.image);
            },

            loadCategories : async function(){
                try{
                    const response = await productService.loadCategories();
                    this.categories = response.data;
                }catch(error){
                    this.flashMessage.error({
                        message: 'Something error. Please refresh !',
                        time: 5000
                    });
                }
            },

            loadProducts : async function(){
                try{
                    const response = await productService.loadProducts();
                    this.products = response.data.data;
                }catch(error){
                    this.flashMessage.error({
                        message: 'Something error. Please refresh !',
                        time: 5000
                    });
                }
            },

            createProduct: async function() {
                let formData = new FormData();
                formData.append('name', this.productData.name);
                formData.append('category_id', this.productData.category_id);
                formData.append('image', this.productData.image);

                try {
                    const response = await productService.createProduct(formData)
                    this.products.unshift(response.data);
                    this.flashMessage.success({
                        message: 'Product stored successfully !',
                        time: 5000
                    });
                    this.productData = {
                        category_id: '',
                        name: '',
                        image: ''
                    };
                    this.hideNewProductModal();
                }catch (error) {
                    switch(error.response.status){
                        case 422:
                            this.errors = error.response.data.errors;
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Something error. Please try again !',
                                time: 5000
                            });
                            break;
                    }
                }
            },

            deleteProduct: async function(product){
                if(! window.confirm(`Are you want to delete ${product.name} ?`)){
                    return;
                }

                try{
                    await productService.deleteProduct(product.id);
                    this.products = this.products.filter(obj => {
                        return obj.id !== product.id;
                    });

                    this.flashMessage.success({
                        message: 'Product deleted successfully !',
                        time: 5000
                    });
                }catch(error){
                    this.flashMessage.error({
                        message: error.response.data.message,
                        time: 5000
                    });
                }
            },

            editProduct: async function(product){
                this.editProductData = {...product};
                this.showEditProductModal();
            },

            updateProduct: async function(){
                try{
                    const formData = new FormData();
                    formData.append('name', this.editProductData.name);
                    formData.append('category_id', this.productData.category_id);
                    formData.append('image', this.editProductData.image);
                    formData.append('_method', 'put');

                    const response = await productService.updateProduct(this.editProductData.id, formData);
                    this.products.map(product => {
                        if (product.id === response.data.id){
                            for(let key in response.data){
                                product[key] = response.data[key];
                            }
                        }
                    });

                    this.hideEditProductModal();
                    this.flashMessage.success({
                        message: 'Product updated successfully !',
                        time: 5000
                    });
                }  catch(error){
                    this.flashMessage.error({
                        message: error.response.data.message,
                        time: 5000
                    });
                }
            },

            hideNewProductModal(){
                this.$refs.newProductModal.hide();
            },

            showNewProductModal(){
                this.$refs.newProductModal.show();
            },

            hideEditProductModal(){
                this.$refs.editProductModal.hide();
            },

            showEditProductModal(){
                this.$refs.editProductModal.show();
            },

            loadMore: async function(){
                try{
                    const response = await productService.loadMore(this.nextPage)
                    if (response.data.current_page < response.data.last_page){
                        this.moreExists = true;
                        this.nextPage = response.data.current_page + 1;
                    }else{
                        this.moreExists = false;
                    }

                    response.data.data.forEach(data => {
                        this.products.push(data)
                    })
                }catch (e) {
                    this.flashMessage.error({
                        message: 'Something error during loading more products !',
                        time: 5000
                    });
                }
            },

            findCategory(category_id){
                let category_name = '';
                this.categories.forEach(category => {
                    if(category_id === category.id){
                        category_name = category.name;
                    }
                });
                return category_name;
            }
        }
    }
</script>
