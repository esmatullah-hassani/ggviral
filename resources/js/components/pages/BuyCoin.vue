<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    

     <center>
          <div class="w-full md:w-1/2 xl:w-1/3">
          <p style="color:blue">
            Please choose how much to buy
          </p>
        <div class="form-group">
            <label>Price : $</label>
            <input type="number" class=" border rounded-md w-96 h-10 focus:outline-none focus:shadow-outline" v-model="product.price" placeholder="Gift Price :">
        </div>
        
        <div id="paypal-button-container" class="mt-10"></div>
      </div>
      </center>
    
  </div>
</template>

<script>
import axios from 'axios';
export default {
    name:"ByeCoin",
    props:['authuser'],
    data(){
        return{
            product:{
                price:1,
                description:"Bye",
            },
        }
    },
    mounted:function(){
        const script = document.createElement("script");
            script.src="https://www.paypal.com/sdk/js?client-id=ASyBLrCpsjB4fKGdtshk9gYl2LukxGtom0EKNX_AQ2ONJZ9utCJx78047JX9I5pDZjYoTE6PUKda0Y6v";
            script.addEventListener("load",this.setLoaded);
            document.body.appendChild(script);
    },
    methods:{
        setLoaded:function(){
            window.paypal.Buttons({
                createOrder:(data, actions) => {
                // This function sets up the details of the transaction, including the amount and line item details.
                return actions.order.create({
                    purchase_units: [{
                    amount: {
                        value: this.product.price,
                    }
                    }]
                });
                },
                onApprove: async(data, actions) => {
                // This function captures the funds from the transaction.
                const order = await actions.order.capture();
                this.submitPayment();
                }
            }).render('#paypal-button-container');
        },

        submitPayment(){
            const formData = new FormData();
            formData.append('price',this.product.price);
            formData.append('user_id',this.authuser.id);
            axios.post("/bye-coin",formData)
            .then((response) => {
                console.log(response);
                    this.$router.push("/chats")
                    .catch((er)=>{})
                    ;
            })
            .catch((err)=>{
                console.log("error "+err);
            });
        }
    }
}
</script>

<style>

</style>