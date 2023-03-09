import React, {useCallback} from 'react'
import { useNavigate } from 'react-router-dom'
import {IndexTable, Badge} from '@shopify/polaris'

const ShopItem = ({selectedResources, item, idx}) => {
    const navigate = useNavigate()

    const handleNavigateEditPage = useCallback((id) => {
        navigate(`/stores/${id}`)
    }, [])

    return (
        <IndexTable.Row
            id={item.id}
            selected={selectedResources.includes(item.id)}
            position={idx}
            onClick={() => handleNavigateEditPage(item.id)}>
            <IndexTable.Cell>{item.name}</IndexTable.Cell>
            <IndexTable.Cell>
                {item.status === 'active' ? (
                    <Badge status='success'>Active</Badge>
                ) : (
                    <Badge status='critical'>Draft</Badge>
                )}
            </IndexTable.Cell>
            <IndexTable.Cell>{item.country}</IndexTable.Cell>
            <IndexTable.Cell>{item.city}</IndexTable.Cell>
            <IndexTable.Cell>{item.address_1}</IndexTable.Cell>
            <IndexTable.Cell>{item.phone}</IndexTable.Cell>
        </IndexTable.Row>
    )
}

export default ShopItem
