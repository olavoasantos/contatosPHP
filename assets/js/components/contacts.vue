<template>
  <div class="contacts flex flex-col h-full w-full max-h-screen sm:w-128 bg-white shadow-md relative">
      <div class="contacts:top bg-primary-dark sm:h-6"></div>

      <!-- Cabeçálio do App -->
      <div class="contacts:header flex items-center bg-primary py-6 sm:py-10 px-4 text-white shadow-md">

        <!-- Botão de voltar -->
        <div v-if="route==='index'" class="flex-center text-2xl font-normal mr-2 p-2 h-10 w-10 rounded-full"></div>
        <div v-if="route!=='index'" @click="goTo('index')" class="flex-center text-2xl font-normal mr-2 p-2 h-10 w-10 hover:bg-primary-dark rounded-full cursor-pointer">
          <i class="fa fa-arrow-left"></i>
        </div>
        <!-- Título -->
        <h1 class="text-2xl sm:text-3xl absolute" style="left: 50%; transform: translateX(-50%)">Contatos</h1>
        
        <!-- Menu -->
        <div class="absolute pin-r" v-show="route === 'show'">
          <o-contact-menu text="white" bg="primary-dark" @edit="edit(contact)" @destroy="destroy(contact)"></o-contact-menu>
        </div>

      </div>

      <!-- Corpo do App -->
      <div class="contacts:list h-full flex-1 pt-4 sm:pt-8 overflow-y-scroll">

        <!-- View contendo a lista de contatos :: route = 'index' -->
        <o-contact
          v-if="route === 'index'" 
          @view="show"
          @edit="edit"
          @destroy="destroy"
          v-for="(contact, id) in contacts"
          :key="`contact-${id}-${contact.name}`"
          :contact="contact"></o-contact>
        
        <!-- View contendo o formulário de criação/edição de contatos :: route = 'form' -->
        <o-contact-form v-if="route === 'form'" @edit="update" @create="store" :form="form"></o-contact-form>

        <!-- View para visualização de um contato :: route = 'show' -->
        <o-contact-view v-if="route === 'show'" :contact="contact"></o-contact-view>

      </div>

      <!-- Botão para criação de um novo contato -->
      <div @click="create" v-if="route==='index'" class="contacts:new-button flex-center text-white text-2xl w-16 h-16 bg-red hover:bg-red-light cursor-pointer rounded-full shadow-lg absolute pin-b pin-r mb-4 mr-4">
        <i class="fa fa-user-plus"></i>
      </div>
  </div>
</template>

<script>
  export default {

    props: {
      /**
       * JSON contendo os contatos cadastrados no
       * banco de dados passados pelo back-end
       * para o componente.
       */
      contactsData: { type: Array }
    },

    created() {
      // Converte os número de telefone de string para JSON
      this.contacts = this.contactsData.map(contact => {
        contact.phone = JSON.parse(contact.phone);

        return contact;
      });

      // Inicia o formulário vazio
      this.resetForm();
    },

    data() {
      return {
        form: {},       // Objeto contendo os dados do formulário
        contact: {},    // Objeto para vizualização de um contato
        contacts: [],   // Array contendo a lista de contatos
        route: 'index', // Endereço da rota ativa
      }
    },

    methods: {
      /**
       * goTo
       * ---
       * Vai para uma rota
       * @param {String}  route Endereço da rota
       */
      goTo(route) {
        this.setRoute(route);
        this.resetForm();
      },

      /**
       * setRoute
       * ---
       * Ativa uma rota.
       * @param {String}  route Endereço da rota
       */
      setRoute(route) {
        this.route = route;
      },

      /**
       * resetForm
       * ---
       * Reseta os dados do formulário.
       */
      resetForm() {
        this.form = {
          action: 'create',
          name: '',
          email: '',
          address: '',
          phone: []
        };
      },

      /**
       * create
       * ---
       * Ativa a rota para o formulário para a criação
       * de um novo contato.
       */
      create() {
        this.setRoute('form');
      },

      /**
       * store
       * ---
       * Envia um request POST assíncrono para o back-end
       * para criar um novo contato e adiciona o novo
       * objeto na lista.
       */
      store() {
        this.$http.post('contatos', this.form).then(({data}) => {  
          data.phone = JSON.parse(data.phone);
          this.contacts.push(data);
          this.resetForm();
          this.setRoute('index');
        });
      },

      /**
       * show
       * ---
       * Ativa a rota para a visualização
       * de um contato.
       * @param {Object}  contact Objeto do contato
       */
      show(contact) {
        this.contact = contact;
        this.setRoute('show');
      },

      /**
       * edit
       * ---
       * Preenche o formulário com os dados do contato
       * e ativa a rota com o formulário
       * de edição.
       */
      edit(contact) {
        this.form.action = 'edit';
        this.form = Object.assign( this.form, contact );
        this.setRoute('form');
      },

      /**
       * update
       * ---
       * Envia um request PATCH assíncrono para o back-end
       * para atualizar os dados de um contato e atualiza
       * o objeto na lista.
       */
      update() {
        this.form._method = 'PATCH';
        this.$http.post('contatos', this.form).then(({data}) => {  
          data.phone = JSON.parse(data.phone);
          this.contacts = this.contacts.map(contact => {
            if(contact.id === data.id) return data;

            return contact;
          });
          this.resetForm();
          this.setRoute('index');
        });
      },

      /**
       * destroy
       * ---
       * Envia um request DELETE assíncrono para o back-end
       * para deletar um contato e remove o
       * contato da lista.
       * @param {Object}  contact Objeto do contato
       */
      destroy(contact) {
        contact._method = 'DELETE';
        this.$http.post('contatos', contact).then(({data}) => {  
          this.contacts = this.contacts.filter(item => item.id !== contact.id);
          this.setRoute('index');
        });
      }
      
    }
  }
</script>
