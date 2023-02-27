import { Page } from "@shopify/polaris";
import { useAppQuery, useAuthenticatedFetch } from "../hooks";

export default async function PageName() {
    const fetch = useAuthenticatedFetch();

    // const {
    //     data,
    // } = useAppQuery({
    //     url: "/api/store/store-1",
    //     method: "POST",
    //     data: {
    //         test: 'data',
    //         test2: 'data2'
    //     },
    //     reactQueryOptions: {
    //         onSuccess: () => {
    //             console.log('test');
    //         },
    //     },
    // });

    // let form = {
    //     name: '123',
    //     address_1: '321',
    //     address_2: 'address_2321',
    //     city: 'city123',
    //     postcode: 'postcode123',
    //     state: 'state123',
    // };
    //
    // const data = await fetch("/api/store/test1", {
    //     method: 'DELETE',
    //     headers: {
    //         "Content-Type": "application/json",
    //     }
    // });

    const data = await fetch("/api/store", {
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    });

    console.log(data);

  return (
    <Page>
        text page
    </Page>
  );
}
