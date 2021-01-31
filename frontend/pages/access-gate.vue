<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">ACCESS GATE</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-plus"
						size="small"
						title="Tambah Gate"
						>TAMBAH GATE</el-button
					>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-input
						v-model="keyword"
						placeholder="Cari"
						prefix-icon="el-icon-search"
						clearable
						size="small"
						@change="
							() => {
								this.pagination.current_page = 1
								fetchData()
							}
						"
					></el-input>
				</el-form-item>
			</el-form>
		</div>

		<el-table
			height="calc(100vh - 171px)"
			stripe
			v-loading="loading"
			:data="tableData"
			@sort-change="sortChange"
			@filter-change="filterChange"
			:default-sort="{ prop: 'name', order: 'ascending' }"
		>
			<el-table-column
				type="index"
				label="#"
				:index="paginated ? pagination.from : 1"
			></el-table-column>
			<el-table-column prop="name" label="Nama"></el-table-column>
			<el-table-column prop="type" label="Jenis"></el-table-column>
			<el-table-column prop="host" label="IP"></el-table-column>
			<el-table-column label="Kamera">
				<template slot-scope="scope">
					{{
						scope.row.camera_list
							? scope.row.camera_list.map((c) => c.name).join(',')
							: ''
					}}
				</template>
			</el-table-column>

			<ActionColumn
				@refreshData="refreshData"
				@openForm="openForm"
				@deleteData="deleteData"
			/>
		</el-table>

		<Pagination
			@current-change="currentChange"
			@size-change="sizeChange"
			:page-sizes="pageSizes"
			:pagination="pagination"
			:paginated="paginated"
			:total="tableData.length"
		/>

		<el-dialog
			title="MEMBER"
			:visible.sync="showForm"
			:before-close="closeForm"
			:close-on-click-modal="false"
		>
			<el-form label-width="180px" label-position="left">
				<el-form-item label="Nama" :class="{ 'is-error': errors.name }">
					<el-input v-model="form.name" placeholder="Nama"></el-input>
					<div class="el-form-item__error" v-if="errors.name">
						{{ errors.name.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="IP" :class="{ 'is-error': errors.host }">
					<el-input v-model="form.host" placeholder="IP"></el-input>
					<div class="el-form-item__error" v-if="errors.host">
						{{ errors.host.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Jenis" :class="{ 'is-error': errors.phone }">
					<el-select
						v-model="form.type"
						placeholder="Jenis"
						style="width: 100%"
					>
						<el-option label="IN" value="IN"></el-option>
						<el-option label="OUT" value="OUT"></el-option>
					</el-select>
					<div class="el-form-item__error" v-if="errors.type">
						{{ errors.type.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Kamera" :class="{ 'is-error': errors.cameras }">
					<el-select
						style="width: 100%"
						v-model="form.cameras"
						placeholder="Kamera"
						filterable
						default-first-option
						remote
						clearable
						multiple
						:remote-method="(q) => getList('/api/camera', 'camera', q)"
					>
						<el-option
							v-for="camera in cameraList"
							:key="camera.id"
							:value="camera.id"
							:label="camera.name"
						></el-option>
					</el-select>

					<div class="el-form-item__error" v-if="errors.cameras">
						{{ errors.cameras.join(', ') }}
					</div>
				</el-form-item>
			</el-form>

			<div slot="footer">
				<el-button icon="el-icon-circle-close" @click="closeForm">
					BATAL
				</el-button>

				<el-button
					type="primary"
					icon="el-icon-success"
					@click="save"
					:loading="loading"
				>
					SIMPAN
				</el-button>
			</div>
		</el-dialog>
	</el-card>
</template>

<script>
import crud from '../mixins/crud';
import dropdown from '../mixins/dropdown';

export default {
  mixins: [crud, dropdown],

  created() {
    this.getList('/api/camera', 'cameraList')
  },

  data() {
    return {
      url: '/api/accessGate'
    }
  }
}
</script>
