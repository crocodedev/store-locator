import React from 'react'
import {TextField, Card, Grid} from '@shopify/polaris'
import '@shopify/polaris/build/esm/styles.css'

function ControlPanel({value, receiveData, handleChangeInfo}) {
	return (
		<Grid>
			<Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 6, xl: 4}}>
				<Card title='Address' sectioned>
					<TextField
						name='country'
						label='Country'
						value={value.country}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'country')}
					/>
					<TextField
						name='city'
						label='City'
						value={value.city}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'city')}
					/>
					<TextField
						name='state'
						label='State/Region'
						value={value.state}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'state')}
					/>
					<TextField
						name='address_1'
						label='Address 1'
						value={value.address_1}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'address_1')}
					/>
					<TextField
						name='address_2'
						label='Address 2'
						value={value.address_2}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'address_2')}
					/>
					<TextField
						name='postcode'
						label='Postcode'
						value={value.postcode}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'postcode')}
					/>
				</Card>
			</Grid.Cell>
			<Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 6, xl: 4}}>
				<Card title='Contacts' sectioned>
					<TextField
						name='phone'
						label='Phone'
						value={value.phone}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'phone')}
					/>
					<TextField
						name='fax'
						label='Fax'
						value={value.fax}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'fax')}
					/>
					<TextField
						name='site'
						label='Site'
						value={value.site}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'site')}
					/>
				</Card>
			</Grid.Cell>
			<Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 6, xl: 4}}>
				<Card title='Socials' sectioned>
					<TextField
						name='social_instagram'
						label='Instagram'
						value={value.social_instagram}
						autoComplete='off'
						onChange={(e) =>
							handleChangeInfo(e, 'social_instagram')
						}
					/>
					<TextField
						name='social_twitter'
						label='Twitter'
						value={value.social_twitter}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'social_twitter')}
					/>
					<TextField
						name='social_facebook'
						label='Facebook'
						value={value.social_facebook}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'social_facebook')}
					/>
					<TextField
						name='social_tiktok'
						label='Tiktok'
						value={value.social_tiktok}
						autoComplete='off'
						onChange={(e) => handleChangeInfo(e, 'social_tiktok')}
					/>
				</Card>
			</Grid.Cell>
		</Grid>
	)
}

export default React.memo(ControlPanel)
