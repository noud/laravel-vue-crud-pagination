

<template>

  <div class="card-body" id="hide_again">
    <table id="" class="table table-bordered table-striped">
      <thead>
      <tr>
         <th>Name</th>
         <th class="check">Action 
          <a data-toggle="modal" data-target="#addNew" style="float: right;cursor: pointer; color: white; padding: 2px;" @click="openModalWindow" class="btn btn-sm btn-warning py-0">
            <i class="fa fa-plus-square"  > Add Category
            </i>
            </a>
          </th>
       </tr>
      </thead>
     <tbody>
        <tr v-for="category in categories.data" :key="category.id">
          <td class="check">
            <input type="checkbox" v-model="deleteItems" :value="`${category.id}`">
          </td>
          <td>{{ category.name }}</td>
          <td>{{ category.is_active == 1 ? 'Active' : 'InActive' }}</td>
          <td class="check">
            <a title="Edit category" class="btn btn-sm btn-dark py-0" style="color:white;cursor: pointer;" @click="editModalWindow(category)">Edit</a> 
            <a class="btn btn-sm btn-danger py-0" @click="deleteCategory(category.id)" style="color:white;">Delete</a>
          </td>
        </tr>
     </tbody>
     <pagination :data="categories" @pagination-change-page="getResults"></pagination>
    </table>

    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">

                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Category</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Category</h5>

            </div>

                <form @submit.prevent="editMode ? updateCategory() : createCategory()">
                <div class="modal-body">
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Name"
                            class="input2 form-control" :class="{ 'is-invalid': form.errors.has('name') }" data-validate="Company name is required">
                            <span class="focus-input2" data-placeholder="Company Name"></span>
                        <has-error :form="form" field="name"></has-error>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-success">Add Category</button>
                </div>

                </form>

                </div>
            </div>
        </div>
  </div>

</template>

<script>

  

    export default {

    data() {
        return {
            editMode: false,
            categories: {},
            form: new Form({
                id: '',
                name : '',
            }),
        }
    },
    methods: {
        openModalWindow(){
           this.editMode = false
           this.form.reset();
           $('#addNew').modal('show');
        },
        fetchingAllCategory() {

          axios.get("api/category").then( data => (this.categories = data.data));


        },
        getResults(page = 1) {
              axios.get('api/category?page=' + page)
                .then(response => {
                  this.categories = response.data;
              });
        },
        createCategory(){

            this.form.post('api/category')
                .then(() => {

                        Fire.$emit('load_category'); 

                        Toast.fire({
                          icon: 'success',
                          title: 'Category created successfully'
                        })

                        $('#addNew').modal('hide');

                })
                .catch(() => {
                   console.log("Error......")
                })

        },
        editModalWindow(supplier){
               this.form.clear();
               this.editMode = true
               this.form.reset();
               $('#addNew').modal('show');
               this.form.fill(supplier)
        },
        updateCategory(){
           this.form.put('api/category/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Category updated successfully'
                    })

                    Fire.$emit('load_category');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        deleteCategory(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                
              if (result.value) {
            
                this.form.delete('api/category/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Category deleted successfully',
                              'success'
                            )
                    
                    Fire.$emit('load_category');

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                        })
                    })
                }

            })
        },
    },
    created(){

        this.fetchingAllCategory();

        Fire.$on('load_category',()=>{
          this.fetchingAllCategory();
        });

    },

}
</script>

<style scoped>
.pagination {
    margin-top: 30px;
    float: right;
}
</style>
 