<template>
	<el-table-column
		fixed="right"
		width="60"
		header-align="center"
		align="center"
	>
		<template slot="header">
			<el-button
				type="text"
				icon="el-icon-refresh"
				@click="$emit('refreshData')"
			></el-button>
		</template>

		<template slot-scope="scope">
			<el-dropdown>
				<span class="el-dropdown-link">
					<i class="el-icon-more"></i>
				</span>
				<el-dropdown-menu slot="dropdown">
					<el-dropdown-item
						icon="el-icon-edit"
						@click.native.prevent="$emit('openForm', scope.row)"
						>Edit</el-dropdown-item
					>
					<el-dropdown-item
						icon="el-icon-delete"
						@click.native.prevent="$emit('deleteData', scope.row.id)"
						>Delete</el-dropdown-item
					>
				</el-dropdown-menu>
			</el-dropdown>
		</template>
	</el-table-column>
</template>

<script>
export default {
  props: ['row']
}
</script>
