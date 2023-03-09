import React from 'react'
import {Page, Grid} from '@shopify/polaris'
import TariffCard from './TariffCard'
import '../../assets/plans/TariffPlanComponent.css'

const arrayOfBenefits = [
	'Valiga dysfaktisk i varade',
	'Valiga dysfaktisk',
	'Valiga dysfaktisk i varade dff g',
	'Valiga dysfaktisk i varade dsd sdd ds'
]

const TariffPlanComponent = () => {
	return (
		<Page
			breadcrumbs={[{content: 'Tariff', url: '/'}]}
			title='Tariff plan'
			divider
			fullWidth>
			<div className='container'>
				<Grid>
					<Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 4, xl: 4}}>
						<TariffCard
							title='Basic'
							description='Lörem ipsum därat vinar gigade, som didäveligt finde i intimitetskoordinator. Valiga dysfaktisk i varade.'
							price='25$'
							arrayOfBenefits={arrayOfBenefits}
						/>
					</Grid.Cell>
					<Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 4, xl: 4}}>
						<TariffCard
							title='Normal'
							description='Lörem ipsum därat vinar gigade, som didäveligt finde i intimitetskoordinator. Valiga dysfaktisk i varade.'
							price='25$'
							arrayOfBenefits={arrayOfBenefits}
							isPopular={true}
						/>
					</Grid.Cell>
					<Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 4, xl: 4}}>
						<TariffCard
							title='Advanced'
							description='Lörem ipsum därat vinar gigade, som didäveligt finde i intimitetskoordinator. Valiga dysfaktisk i varade.'
							price='25$'
							arrayOfBenefits={arrayOfBenefits}
						/>
					</Grid.Cell>
				</Grid>
			</div>
		</Page>
	)
}

export default TariffPlanComponent
