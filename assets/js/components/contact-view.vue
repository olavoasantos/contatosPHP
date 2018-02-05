<template>
  <div class="contact">
    <!-- Nome -->
    <div class="flex my-2 mx-4 border shadow rounded">
      <div class="contact:info flex-center flex-1">
        <div class="contact:avatar flex justify-center items-start p-4">
          <o-avatar :name="contact.name"></o-avatar>
        </div>
        <h3 class="flex py-4 flex-col flex-1 pl-4">
          <span>{{ contact.name }}</span>
        </h3>
      </div>
    </div>

    <!-- E-mail -->
    <div class="flex my-2 mx-4 border shadow rounded">
      <div class="contact:info flex-center flex-1">
        <div class="contact:avatar flex justify-center items-start p-4 text-xl">
          <i class="fa fa-envelope"></i>
        </div>
        <div class="flex py-4 flex-col flex-1 pl-4">
          <span>{{ contact.email }}</span>
        </div>
      </div>
    </div>

    <!-- Endereço -->
    <div class="flex my-2 mx-4 border shadow rounded">
      <div class="contact:info flex-center flex-1">
        <div class="contact:avatar flex justify-center items-start p-4 text-2xl">
          <i class="fa fa-home"></i>
        </div>
        <div class="flex py-4 flex-col flex-1 pl-4">
          <span>{{ contact.address }}</span>
        </div>
      </div>
    </div>

    <!-- Telefones -->
    <div class="flex my-2 mx-4 border shadow rounded">
      <div class="contact:info flex flex-1">
        <div class="contact:avatar flex justify-center items-start p-4 text-2xl">
          <i class="fa fa-phone"></i>
        </div>
        <div class="flex flex-col flex-1">
          <div v-for="phone in contact.phone" :key="`contact-phone-${phone.number}`" class="flex py-4 flex-col flex-1 pl-4">
            <span>{{ phone.number }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    
    props: {
       /**
       * Objeto contendo as informções do contato.
       */
      contact: { type: Object }
    },

    methods: {
      /**
       * edit
       * ---
       * Emite um evento para ativar o formulário
       * de edição de um contato.
       */
      edit() {
        this.$emit('edit', this.contact);
      },

      /**
       * destroy
       * ---
       * Emite um evento para deletar um contato.
       */
      destroy() {
        this.$emit('destroy', this.contact);
      },

    }
  }
</script>
