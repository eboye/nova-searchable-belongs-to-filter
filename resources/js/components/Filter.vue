<template>
  <div>
    <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3 hidden">
      {{ filter.name }}
    </h3>

    <div class="p-2">
      <custom-search-input
          @input="performSearch"
          @clear="handleChange"
          @selected="handleChange"
          :value="value"
          :data="availableResources"
          :clearable="true"
          trackBy="value"
          searchBy="display"
          :placeholder="filter.name"
      >
        <div
            slot="default"
            v-if="value"
            class="flex items-center"
        >
          <div
              v-if="value.avatar"
              class="mr-3"
          >
            <img alt="avatar"
                 :src="value.avatar"
                 class="w-8 h-8 rounded-full block"
            />
          </div>

          {{ value.display }}
        </div>

        <div
            slot="option"
            slot-scope="{ option, selected }"
            class="flex items-center"
        >
          <div
              v-if="option.avatar"
              class="mr-3"
          >
            <img alt="avatar"
                 :src="option.avatar"
                 class="w-8 h-8 rounded-full block"
            />
          </div>

          {{ option.display }}
        </div>
      </custom-search-input>

    </div>
  </div>
</template>

<script>
import {PerformsSearches} from "laravel-nova"
import storage            from '../storage/BelongsToFieldStorage'
import CustomSearchInput  from "./CustomSearchInput";

export default {
  components: {CustomSearchInput},
  mixins: [PerformsSearches],

  props: {
    resourceName: {
      type: String,
      required: true,
    },
    filterKey: {
      type: String,
      required: true,
    },
  },

  methods: {
    getAvailableResources() {
      return storage.fetchAvailableResources(
          this.resourceName,
          this.fieldAttribute,
          {
            params: {
              search: this.search,
            },
          }
      ).then(({data: {resources}}) => {
        this.availableResources = resources;
      });
    },

    handleChange(resource) {
      this.$store.commit(`${this.resourceName}/updateFilterState`, {
        filterClass: this.filterKey,
        value: resource
      });

      this.$emit("change");
      this.$emit("filterChanged");
    },
  },
  watch: {
    value(value) {
      this.handleChange(value);
    }
  },

  computed: {
    filter() {
      return this.$store.getters[`${this.resourceName}/getFilter`](
          this.filterKey
      );
    },

    fieldAttribute() {
      return this.filter.fieldAttribute;
    },

    value() {
      return this.filter.currentValue;
    },
  },
};
</script>
