<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                    <div class="card-body">
                        <form v-on:submit.prevent="register">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label class="small mb-1" >
                                        Full Name
                                    </label>
                                    <input v-model="user.name" class="form-control py-4" name="name" id="inputFirstName" type="text" placeholder="Enter first name"/>
                                    <div style="color: red" v-if="errors.name">{{errors.name[0]}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                <input v-model="user.email" class="form-control py-4" name="email" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address"/>
                                <div style="color: red" v-if="errors.email">{{errors.email[0]}}</div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">Password</label>
                                        <input v-model="user.password" class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password"/>
                                        <div style="color: red" v-if="errors.password">{{errors.password[0]}}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputConfirmPassword">
                                            Confirm Password
                                        </label>
                                        <input v-model="user.password_confirmation" class="form-control py-4" name="password_confirmation" id="inputConfirmPassword" type="password" placeholder="Confirm password"/>
                                        <div style="color: red" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4 mb-0">
                                <button class="btn btn-primary btn-block" type="submit">Create Account</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <router-link to="/login" class="small">Have an account? Go to login</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as auth from '../../services/auth_service.js';

    export default {
        name: "Register",
        data(){
          return {
              user: {
                  name: '',
                  email: '',
                  password: '',
                  password_confirmation: ''
              },
              errors: {}
          }
        },
        methods: {
            register : async function(){
                try{
                    await auth.register(this.user);
                    this.errors = {};
                    this.$router.push('/login')
                }catch(error){
                    switch(error.response.status){
                        case 422:
                            this.errors = error.response.data.errors;
                            break;
                        case 500:
                            this.flashMessage.error({
                                message: error.response.data.message,
                                time: 5000
                            });
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Something error. Please try again !',
                                time: 5000
                            });
                            break;
                    }
                }
            }
        },

        created() {
            document.querySelector('body').style.backgroundColor  = 'grey';
        }
    }
</script>
