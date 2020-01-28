 <template>
  <div>
    <h3 v-if="heading && personlist.length">{{ heading }}</h3>
    <table class="pure-table pure-table-horizontal" v-if="personlist.length">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Email</th>
          <th scope="col">
            <label for="language">
              <span>
                Orcid <i
                  v-tooltip="{ content: messages.orcid, class: 'tooltip-custom tooltip-other-custom' }"
                  class="far fa-lg fa-question-circle"
                ></i>
              </span>
            </label>
          </th>
          <th></th>
        </tr>
      </thead>
      <draggable
        v-bind:list="personlist"
        tag="tbody"
        v-on:start="isDragging=true"
        v-on:end="isDragging=false"
      >
        <tr
          v-for="(item, index) in personlist"
          v-bind:key="item.id"
          v-bind:class="[item.status==true ? 'activeClass' : 'inactiveClass']"
        >
          <td scope="row">{{ index + 1 }}</td>
          <td>
            <input
              v-bind:name="heading+'['+index+'][id]'"
              class="form-control"
              v-model="item.id"
              readonly
              data-vv-scope="step-1"
            />
          </td>
          <td>
            <input
              v-bind:name="heading+'['+index+'][first_name]'"
              class="form-control"
              placeholder="[FIRST NAME]"
              v-model="item.first_name"
              v-bind:readonly="item.status==1"
              v-validate="'required'"
              data-vv-scope="step-1"
            />
          </td>
          <td>
            <input
              v-bind:name="heading+'['+index+'][last_name]'"
              class="form-control"
              placeholder="[LAST NAME]"
              v-model="item.last_name"
              v-bind:readonly="item.status==1"
              v-validate="'required'"
              data-vv-scope="step-1"
            />
          </td>
          <td>
            <!-- v-validate="'required|email'" -->
            <input
              v-bind:name="heading+'['+index+'][email]'"
              class="form-control"
              placeholder="[EMAIL]"
              v-model="item.email"
              v-validate="{required: true, email: true, unique: [personlist, index, 'email']}"
              v-bind:readonly="item.status==1"
              data-vv-scope="step-1"
            />
          </td>
          <td>
            <input
              v-bind:name="heading+'['+index+'][identifier_orcid]'"
              class="form-control"
              placeholder="[ORCID optional]"
              v-model="item.identifier_orcid"
              v-bind:readonly="item.status==1"
              data-vv-scope="step-1"
            />
          </td>
          <td>
            <button
              class="pure-button button-small is-warning"
              @click.prevent="removeAuthor(index)"
            >
              <i class="fa fa-trash"></i>
            </button>
          </td>
        </tr>
      </draggable>
    </table>
  </div>
</template>

<script lang="ts">
import draggable from "vuedraggable";
import { Component, Inject, Vue, Prop, Watch } from "vue-property-decorator";

@Component({
  components: { draggable }
})
export default class PersonTable extends Vue {
  @Inject("$validator") readonly $validator;
  // inject: {
  //   $validator: "$validator"
  // },
  name: "person-table";
  // components: {
  //   draggable
  // },

  editable = true;
  isDragging = false;
  delayedDragging = false;

  @Prop({ default: true, type: Array })
  personlist;
  @Prop(Number)
  rowIndex;
  @Prop(String)
  heading;
  @Prop({ required: true, type: Array })
  messages;

  // props: {
  //   personlist: {
  //     type: Array,
  //     required: true
  //   },
  //   rowIndex: {
  //     type: Number
  //   },
  //   heading: String
  // },

  itemAction(action, data, index) {
    console.log("custom-actions: " + action, data.full_name, index);
  }

  removeAuthor(key) {
    this.personlist.splice(key, 1);
  }

  onMove({ relatedContext, draggedContext }) {
    const relatedElement = relatedContext.element;
    const draggedElement = draggedContext.element;
    return (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed;
  }
}
</script>

<style>
.custom-actions button.ui.button {
  padding: 8px 8px;
}
.custom-actions button.ui.button > i.icon {
  margin: auto !important;
}
.activeClass {
  background-color: aquamarine;
}
.inactiveClass {
  background-color: orange;
}
</style>